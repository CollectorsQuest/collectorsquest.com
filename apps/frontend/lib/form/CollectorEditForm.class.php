<?php

class CollectorEditForm extends CollectorForm
{
  public function configure()
  {
    parent::configure();

    $this->setupPasswordFields();
    $this->embedProfileForm();

    $this->widgetSchema->setLabels(array(
       'display_name' => 'Nickname',
    ));

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupPasswordFields()
  {
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(), array(
        'placeholder' => 'Set new password here',
    ));
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword(array(
        'label'       => 'Confirm Password'
      ), array(
        'placeholder' => 'Confirm your new password'
    ));

    $this->validatorSchema['password'] = new sfValidatorString(
      array(
        'min_length' => 6,
        'max_length' => 50,
        'required'   => false,
      ), array(
        'max_length' => 'The password is too long (%max_length% characters max).',
        'min_length' => 'The password is too short (%min_length% characters min).',
    ));
    $this->validatorSchema['password_again'] = new sfValidatorPass();

    $this->mergePostValidator(new sfValidatorSchemaCompare(
      'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
      array('throw_global_error' => true),
      array('invalid' => 'The two passwords do not match, please enter them again!')
    ));
  }

  protected function embedProfileForm()
  {
    $profile_form = new CollectorProfileEditForm($this->getObject()->getProfile());
    $profile_form->widgetSchema->setFormFormatterName('Bootstrap');

    $this->mergeForm($profile_form);
  }

  protected function unsetFields()
  {
    unset ($this['username']);
    unset ($this['email']);
    unset ($this['has_completed_registration']);
    unset ($this['user_type']);
    unset ($this['items_allowed']);
    unset ($this['max_collectibles_for_sale']);
    unset ($this['locale']);
    unset ($this['is_spam']);
    unset ($this['is_public']);
    // merged from CollectorProfileEditForm
    unset ($this['about_new_item_every']);

    parent::unsetFields();
  }
}