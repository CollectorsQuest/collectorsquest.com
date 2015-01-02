<?php

/**
 * @method Comment getObject()
 */
class FrontendCommentForm extends BaseCommentForm
{

  /** @var sfUser */
  protected $sf_user;

  /** @var BaseObject */
  protected $model_object;

  /**
   * @param     sfUser $sf_user
   * @param     BaseObject $model_object
   * @param     array $options
   * @param     string $CSRFSecret
   */
  public function __construct(
    cqBaseUser $sf_user,
    BaseObject $model_object = null,
    $options = array(),
    $CSRFSecret = null
  ) {
    $this->sf_user = $sf_user;
    $this->model_object = $model_object;

    $object = new Comment();

    parent::__construct($object, $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->setupBodyField();
    $this->setupAuthorNameField();
    $this->setupAuthorEmailField();
    $this->setupTokenField();
    $this->setupRefererField();
    $this->setupIpAddressField();

    $this->widgetSchema->setLabels(array(
        'is_notify' => 'Notify me of follow-up comments by email.',
    ));

    // set html attributes
    $this->widgetSchema['body']->setAttribute('required', 'required');
    $this->widgetSchema['author_email']->setAttribute('type', 'email');

    $this->unsetFields();

    $this->widgetSchema->setFormFormatterName('Bootstrap');

    $this->mergePostValidator(
      new FrontendCommentFormValidatorSchema($this->sf_user)
    );
    $this->mergePostValidator(new iceSpamControlValidatorSchema(array(
        'credentials' => iceSpamControl::CREDENTIALS_ALL,
        'fields' => array(
            'author_email' => 'email',
            $this->getIpAddressFieldName() => 'ip',
        ),
        'force_skip_check' => array($this, 'forceSkipSpamCheck'),
      ), array(
        'spam' => 'We are sorry we could not add your comment. Please try again later.',
    )));

    $this->mergePostValidator(new AyahValidatorSchema(array(), array(
        'spam' => 'We are sorry we could not add your comment. Please try again later.',
    )));

    $this->mergePostValidator(new cqValidatorSchemaTimeoutCheck($this->sf_user, array(
        'type' => cqValidatorSchemaTimeoutCheck::TIMEOUT_TYPE_COMMENTS,
        'threshold' => sfConfig::get('app_comments_timeout_threshold', 6),
        'timeout_duration' => sfConfig::get('app_comments_timeout_duration', '30 minutes'),
        'timeout_check_period' => sfConfig::get('app_comments_timeout_check_period', '60 minutes'),
        'timeout_check_period_increase_for_unsigned' => sfConfig::get('app_comments_timeout_check_period_increase_for_unsigned', '0 minutes'),
        'force_skip_check' => array($this, 'forceSkipSpamCheck'),
    )));
  }

  /**
   * Callback used to skip spam and timeout checks for specific users
   *
   * @param  array The submitted form values after base validation
   * @return boolean
   */
  public function forceSkipSpamCheck($values)
  {
    if ($this->sf_user->isAdmin())
    {
      // if the user is logged in the backend as admin, skip spam check
      return true;
    }

    if ( $collector = $this->sf_user->getCollector($strict = true) )
    {
      // skip if current user is one of the predefined skip spam users list
      if (in_array($collector->getUsername(), sfConfig::get('app_skip_spam_check_by_username', array())))
      {
        return true;
      }
    }

    // also skip if the user is commenting on his own item
    $model_object = CommentPeer::retrieveFromCommentableToken(
      $values['token'],
      $this->sf_user
    );
    if ($this->sf_user->isOwnerOf($model_object))
    {
      return true;
    }

    return false;
  }

  protected function setupBodyField()
  {
    $this->validatorSchema['body'] = new sfValidatorAnd(array(
      new sfValidatorCallback(array('callback' => function($validator, $value) {
        return IceStatic::cleanText($value);
      })),
      new sfValidatorString(array(
          'trim' => true,
          'required' => true,
      )),
    ));
  }

  protected function setupAuthorNameField()
  {
    $this->validatorSchema['author_name'] = new sfValidatorString(array(
        'trim' => true,
        'required' => !$this->isAuthenticated(),
    ));
  }

  protected function setupAuthorEmailField()
  {
    $this->validatorSchema['author_email'] = new sfValidatorAnd(array(
      new sfValidatorString(array(
          'trim' => true,
          'required' => !$this->isAuthenticated(),
      )),
      new sfValidatorEmail(array(
          'trim' => true,
          'required' => !$this->isAuthenticated(),
      ))
    ), array(
        'required' => !$this->isAuthenticated(),
    ));
  }

  protected function setupTokenField()
  {
    $this->widgetSchema['token'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['token'] = new sfValidatorString();

    if ($this->model_object)
    {
      $token = $this->getDefault('token') ?:
        CommentPeer::addCommentableTokenToSession($this->model_object, $this->sf_user);

      $this->setDefault('token', $token);
    }
  }

  protected function setupRefererField()
  {
    $this->widgetSchema['referer'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['referer'] = new sfValidatorString();
  }

  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);

    $this->getObject()->setModelObject(CommentPeer::retrieveFromCommentableToken(
      $this->getValue('token'),
      $this->sf_user
    ));

    if ($this->isAuthenticated())
    {
      $this->getObject()->setCollector($this->sf_user->getCollector());
    }
  }

  protected function unsetFields()
  {
    unset ($this['id']);
    unset ($this['disqus_id']);
    unset ($this['model']);
    unset ($this['model_id']);
    unset ($this['parent_id']);
    unset ($this['collection_id']);
    unset ($this['collectible_id']);
    unset ($this['collector_id']);
    unset ($this['is_hidden']);
    unset ($this['is_spam']);
    unset ($this['author_url']);
    unset ($this['subject']);
    unset ($this['created_at']);
  }

  protected function isAuthenticated()
  {
    return $this->sf_user->isAuthenticated();
  }

}
