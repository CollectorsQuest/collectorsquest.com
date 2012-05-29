<?php

class CollectorEditForm extends CollectorForm
{

  /** @var CollectorProfileForm */
  protected $profile_form;

  public function configure()
  {
    parent::configure();

    $this->setupPasswordFields();
    $this->embedProfileForm();
    $this->setupProfileGenderField();
    $this->setupProfileCollectorType();
    $this->setupProfileWebsite();

    $this->widgetSchema->setLabels(array(
        'display_name' => 'Screen Name',
        'collector_type' => 'Collector Type',
        'country_iso3166' => 'Country',
        'about_what_you_collect' => 'What do you collect?',
        'about_collections' => 'Your collections are',
        'about_purchase_per_year' => 'How many times a year do you purchase?',
        'about_most_expensive_item' => "What's the most you've spent on an item?",
        'about_annually_spend' => 'How much do you spend annually?',
        'about_interests' => 'Your interests are',
        'website' => 'Personal Website',
    ));

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupPasswordFields()
  {
    $this->widgetSchema['old_password'] = new sfWidgetFormInputPassword(array(), array(
        'placeholder' => 'Enter your old password'
    ));
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(), array(
        'placeholder' => 'Set new password here',
    ));
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword(array(
        'label'       => 'Confirm Password'
      ), array(
        'placeholder' => 'Confirm your new password'
    ));

    $this->validatorSchema['old_password'] = new sfValidatorPass();
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

    $this->mergePostValidator(new sfValidatorAnd(array(
        new CollectorEditFormPasswordSchemaValidator(null, array(
            'collector' => $this->getObject(),
        )),
        new sfValidatorSchemaCompare(
          'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
          array('throw_global_error' => true),
          array('invalid' => 'The two passwords do not match, please enter them again!')),
    )));
  }

  protected function embedProfileForm()
  {
    $this->mergeForm($this->getProfileForm());
  }

  protected function setupProfileGenderField()
  {
    $this->widgetSchema['gender'] = new sfWidgetFormSelectRadio(array(
        'choices' => array('f' => 'Female', 'm' => 'Male', '' => 'Rather not say'),
        'formatter' => array($this, 'inlineRadioInputFormatter'),
    ));
  }

  protected function setupProfileCollectorType()
  {
    $this->widgetSchema['collector_type'] = new sfWidgetFormSelectRadio(array(
        'choices' => $this->getProfileForm()->getCollectorTypeChoices(),
        'formatter' => array($this, 'inlineRadioInputFormatter'),
    ));
  }

  protected function setupProfileWebsite()
  {
    if (!$this->getObject()->getIsSeller())
    {
      $this->widgetSchema['website']->setAttributes(array(
          'placeholder' => 'This feature is only available for sellers',
          'disabled' => 'disabled',
          'class' => 'disabled',
      ));
    }
  }

  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);

    $this->getObject()->getProfile()->fromArray($values, BasePeer::TYPE_FIELDNAME);
  }

  protected function unsetFields()
  {
    unset ($this['id']);
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


  /**
   * @return    CollectorProfileForm
   */
  protected function getProfileForm()
  {
    if (null === $this->profile_form)
    {
      $this->profile_form = new CollectorProfileEditForm(
        $this->getObject()->getProfile());
      $this->profile_form->widgetSchema->setFormFormatterName('Bootstrap');
    }

    return $this->profile_form;
  }

  public function inlineRadioInputFormatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $rows[] = $widget->renderContentTag('label', $input['input'].strip_tags($input['label']), array('class' => 'radio inline'));
    }

    return !$rows ? '' : $widget->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }

}
