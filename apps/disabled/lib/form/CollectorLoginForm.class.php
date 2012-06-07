<?php

class CollectorLoginForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInputText(),
      'password'  => new sfWidgetFormInputPassword(),
      'email'     => new sfWidgetFormInputText(),
      'remember'  => new sfWidgetFormInputCheckbox()
    ));

    $this->setValidators(array(
      'username'  => new sfValidatorPropelChoice(array('model' => 'Collector', 'column' => 'username', 'required' => true)),
      'password'  => new sfValidatorString(array('max_length' => 64, 'required' => true)),
      'email'     => new sfValidatorEmail(array('required' => false)),
      'remember'  => new sfValidatorPass()
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }
}
