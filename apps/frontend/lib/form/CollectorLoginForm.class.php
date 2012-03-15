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
      'username'  => new sfValidatorString(),
      'password'  => new sfValidatorString(),
      'remember'  => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('login[%s]');
    $this->mergePostValidator(new cqValidatorSchemaCollector(null, array(
        'throw_global_error' => true,
    )));
  }

}
