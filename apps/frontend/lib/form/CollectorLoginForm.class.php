<?php

class CollectorLoginForm extends BaseForm
{

  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInputText(array(
      ), array(
          'required' => 'required',
          'placeholder' => 'Username',
      )),
      'password'  => new sfWidgetFormInputPassword(array(), array(
          'required' => 'required',
          'placeholder' => 'Password'
      )),
      'remember'  => new sfWidgetFormInputCheckbox(array(
            'label' => 'Remember me',
      )),
    ));

    $this->setValidators(array(
      'username'  => new sfValidatorString(),
      'password'  => new sfValidatorString(),
      'remember'  => new sfValidatorBoolean(),
    ));
    $this->mergePostValidator(new cqValidatorSchemaCollector(null, array(
        'throw_global_error' => true,
    )));
    $this->disableLocalCSRFProtection();

    $this->widgetSchema->setNameFormat('login[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

}
