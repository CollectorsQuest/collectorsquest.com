<?php

class CollectorEditForm extends BaseFormPropel
{
  public function setup()
  {
    $years = array_combine(range(date('Y') - 100, date('Y')), range(date('Y') - 100, date('Y')));

    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'photo'          => new sfWidgetFormInputFile(),
      'display_name'   => new sfWidgetFormInputText(),
      'email'          => new sfWidgetFormInputText(),
      'password'       => new sfWidgetFormInputPassword(),
      'password_again' => new sfWidgetFormInputPassword()
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'Collector', 'column' => 'id', 'required' => true)),
      'photo'          => new sfValidatorFile(array('required' => false, 'mime_categories' => 'web_images')),
      'display_name'   => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'email'          => new sfValidatorEmail(array('required' => true)),
      'password'       => new sfValidatorString(array('min_length' => 6, 'max_length' => 50, 'required' => false)),
      'password_again' => new sfValidatorPass()
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        array('throw_global_error' => true),
        array('invalid' => 'The two passwords do not match, please enter them again!')
      )
    );

    $profile = new CollectorProfileEditForm($this->getObject()->getProfile());
    $this->embedForm('profile', $profile);

    $this->widgetSchema->setNameFormat('collector[%s]');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collector';
  }
}
