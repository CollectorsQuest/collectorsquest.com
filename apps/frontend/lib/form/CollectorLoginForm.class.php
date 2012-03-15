<?php

class CollectorLoginForm extends BaseForm
{

  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInputText(),
      'password'  => new sfWidgetFormInputPassword(),
      'remember'  => new sfWidgetFormInputCheckbox()
    ));

    $this->setValidators(array(
      'username'  => new sfValidatorPropelChoice(array(
          'model' => 'Collector',
          'column' => 'username',
          'required' => true
      )),
      'password'  => new sfValidatorString(array(
          'max_length' => 64,
          'required' => true
      )),
      'remember'  => new sfValidatorBoolean()
    ));

    $this->validatorSchema->setPostValidator(new cqValidatorSchemaCollector());
  }

}
