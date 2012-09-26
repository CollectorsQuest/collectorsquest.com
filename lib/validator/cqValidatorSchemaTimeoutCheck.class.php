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
   *  - type: One of TIMEOUT_TYPE_COMMENTS or TIMEOUT_TYPE_PRIVATE_MESSAGES
   *  - threshold: Threshold for timeout activation
   *  - timeout_check_period: How far back (strototime compatible) to check for
   *                          threshold breach
   *  - timeout_duration: How long will the timeout be in effect for
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
    if (self::TIMEOUT_TYPE_PRIVATE_MESSAGES == $this->getOption('type'))
    {
      $this->executePrivateMessagesCheck($values);
    }
    elseif (self::TIMEOUT_TYPE_COMMENTS == $this->getOption('type'))
    {
      throw new RuntimeException('timeout for comments not implemented yet');
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

}
