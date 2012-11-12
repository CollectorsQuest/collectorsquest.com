<?php

/**
 * A timeouts validator for PMs and comments
 */
class cqValidatorSchemaTimeoutCheck extends sfValidatorSchema
{
  const TIMEOUT_TYPE_COMMENTS = 'comments';
  const TIMEOUT_TYPE_PRIVATE_MESSAGES = 'private_messages';

  protected static $allowed_timeout_types = array(
      self::TIMEOUT_TYPE_COMMENTS,
      self::TIMEOUT_TYPE_PRIVATE_MESSAGES,
  );

  /** @var cqFrontendUser */
  protected $sf_user;

  /**
   * Remove $fields from the constructor and instead require sfUser
   *
   * @param     cqFrontendUser $sf_user
   * @param     array $options
   * @param     array $messages
   */
  public function __construct(
    cqFrontendUser $sf_user,
    $options = array(),
    $messages = array()
  ) {
    $this->sf_user = $sf_user;

    parent::__construct(null, $options, $messages);

    // check if we have the right required timeout_type
    if (!in_array($this->getOption('type'), self::$allowed_timeout_types))
    {
      throw new RuntimeException(sprintf(
        'Unrecognized timeout type, you must supply one of %s.',
        implode(', ', self::$allowed_timeout_types)
      ));
    }
  }

  /**
   * Options:
   *  required:
   *  - type: One of TIMEOUT_TYPE_COMMENTS or TIMEOUT_TYPE_PRIVATE_MESSAGES
   *  - threshold: Threshold for timeout activation
   *  - timeout_check_period: How far back (strototime compatible) to check for
   *                          threshold breach
   *  - timeout_duration: How long will the timeout be in effect for
   *
   *  optional:
   *  - ip_field: Used by the comments check for non logged in users
   *  - timeout_check_period_increase_for_unsigned: How much longer the check period
   *                                                should be for unsigned users
   *  - force_skip_check:  boolean/callable - force timeout check to be skipped
   *
   * Messages:
   *  - private_message_timeout
   *  - comment_timeout
   *
   * @param     array $options
   * @param     array $messages
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('type');
    $this->addRequiredOption('threshold');
    $this->addRequiredOption('timeout_duration');
    $this->addRequiredOption('timeout_check_period');
    $this->addOption('ip_field', 'ip_address');
    $this->addOption('timeout_check_period_increase_for_unsigned', '0 minutes');
    $this->addOption('force_skip_check', false);

    $this->addMessage('private_message_timeout', 'You cannot send any more PMs right now. Try again later.');
    $this->addMessage('comment_timeout', 'You cannot post any more comments right now. Try again later.');
  }

  /**
   *
   * @param     array $values
   * @return    array
   *
   * @throws RuntimeException
   */
  protected function doClean($values)
  {
    // check for forcible skip timeout check, eitehr directly or by callable
    $skip_check = $this->getOption('force_skip_check');
    if (true === $skip_check ||
        (is_callable($skip_check) && true === call_user_func($skip_check, $values))
    ) {
      // we are forced to skip the check by the form, just return the values
      return $values;
    }

    if (self::TIMEOUT_TYPE_PRIVATE_MESSAGES == $this->getOption('type'))
    {
      $this->executePrivateMessagesCheck($values);
    }
    elseif (self::TIMEOUT_TYPE_COMMENTS == $this->getOption('type'))
    {
      $this->executeCommentsCheck($values);
    }

    return $values;
  }

  /**
   * Check for private messages timeout
   */
  public function executePrivateMessagesCheck($values)
  {
    if (( $collector = $this->sf_user->getCollector($strct = true) ))
    {
      if ($collector->getTimeoutIgnoreForUser())
      {
        // timeouts check should not be applied to this user
        return;
      }

      $sent_pms = PrivateMessageQuery::create()
        ->filterByCollectorRelatedBySender($collector)
        ->filterByCreatedAt(
          strtotime('-'.$this->getOption('timeout_check_period')),
          Criteria::GREATER_EQUAL
        )
        ->count();

      $now = new DateTime();
      $timeout = $collector->getTimeoutPrivateMessagesAt(null);
      // if we have hit the threshold and are not currently in a timeout
      if ($sent_pms != 0 && 0 == $sent_pms % $this->getOption('threshold') && $now > $timeout)
      {
        $collector->setTimeoutPrivateMessagesAt(
          strtotime('+'.$this->getOption('timeout_duration'))
        );
        $collector->save();
      }

      // we may have set a new timeout in the previous if, so get it again
      $timeout = $collector->getTimeoutPrivateMessagesAt(null);
      if ($now < $timeout)
      {
        // we are currently in timeout, so throw a validator exception
        throw new sfValidatorError($this, 'private_message_timeout');
      }
    }
  }

  /**
   * Check for comments timeout
   */
  public function executeCommentsCheck($values)
  {
    // first we check if messages are being sent by a collector,
    // and do the appropriate checks
    if (( $collector = $this->sf_user->getCollector($strct = true) ))
    {
      if ($collector->getTimeoutIgnoreForUser())
      {
        // timeouts check should not be applied to this user
        return;
      }

      $num_comments = CommentQuery::create()
        ->filterByCollector($collector)
        ->filterByCreatedAt(
          strtotime('-'.$this->getOption('timeout_check_period')),
          Criteria::GREATER_EQUAL
        )
        ->count();

      $now = new DateTime();
      $timeout = $collector->getTimeoutCommentsAt(null);
      // if we have hit the threshold and are not currently in a timeout
      if ($num_comments != 0 && 0 == $num_comments % $this->getOption('threshold') && $now > $timeout)
      {
        $collector->setTimeoutCommentsAt(
          strtotime('+'.$this->getOption('timeout_duration'))
        );
        $collector->save();
      }

      // we may have set a new timeout in the previous if, so get it again
      $timeout = $collector->getTimeoutCommentsAt(null);
      if ($now < $timeout)
      {
        // we are currently in timeout, so throw a validator exception
        throw new sfValidatorError($this, 'comment_timeout');
      }
    }
    // we are dealing with a user that is not logged in
    else
    {
      // we filter by the user's IP address to get the number of recently posted
      // comments. If we have more comments than the treshold in the last
      // %timeout_check_period%  + %timeout_check_period_increase_for_unsigned%
      // minutes, then we preven the posting of the current comment.
      $num_comments = CommentQuery::create()
        ->filterByIpAddress($values[$this->getOption('ip_field')])
        ->filterByCreatedAt(
          strtotime(
            '-'.$this->getOption('timeout_check_period_increase_for_unsigned'),
            $for_strtotime = strtotime('-'.$this->getOption('timeout_check_period'))
          ),
          Criteria::GREATER_EQUAL
        )
        ->count();

      // if we have hit the threshold
      if ($num_comments >= $this->getOption('threshold'))
      {
        // stop this comment
        throw new sfValidatorError($this, 'comment_timeout');
      }
    }
  }

}
