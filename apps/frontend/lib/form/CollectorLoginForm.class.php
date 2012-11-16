<?php

class CollectorLoginForm extends BaseForm
{

  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInputText(array(
          'label' => 'Username',
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
      'goto' => new sfWidgetFormInputHidden(array(), array(
          'class' => 'set-value-to-href',
      ))
    ));

    $this->setValidators(array(
      'username'  => new cqValidatorCollectorUsername(),
      'password'  => new sfValidatorString(),
      'remember'  => new sfValidatorBoolean(),
      'goto'      => new sfValidatorPass(),
    ));
    $this->mergePostValidator(new cqValidatorSchemaCollector(null, array(
        'throw_global_error' => true,
    )));
    $this->disableLocalCSRFProtection();

    $this->widgetSchema->setNameFormat('login[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

}
