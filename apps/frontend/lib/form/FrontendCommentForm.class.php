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

    if ($this->isAuthenticated())
    {
      $object->setCollector($this->sf_user->getCollector());
    }

    parent::__construct($object, $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->setupTokenField();

    $this->widgetSchema->setLabels(array(
        'is_notify' => 'Notify me of follow-up comments by email.',
    ));

    // set html attributes
    $this->widgetSchema['body']->setAttribute('required', 'required');
    $this->widgetSchema['author_email']->setAttribute('type', 'email');

    $this->unsetFields();
  }

  protected function setupTokenField()
  {
    $this->widgetSchema['token'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['token'] = new sfValidatorString();

    if ($this->model_object)
    {
      $this->setDefault('token', $this->getDefault('token')
        ?: CommentPeer::addCommentableTokenToSession($this->model_object, $this->sf_user));
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
    unset ($this['author_url']);
    unset ($this['subject']);
    unset ($this['ip_address']);
    unset ($this['created_at']);
  }

  protected function isAuthenticated()
  {
    return $this->sf_user->isAuthenticated();
  }

}