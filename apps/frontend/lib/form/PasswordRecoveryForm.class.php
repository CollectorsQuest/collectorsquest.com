<?php

/**
 * A simple password recovery form, with gdCaptcha
 */
class PasswordRecoveryForm extends BaseForm
{

  public function configure()
  {
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array(), array(
        'type' => 'email',
    ));
    $this->widgetSchema['captcha'] = new cqWidgetBootstrapCaptcha(array(
        'width' => 200,
        'height' => 50,
    ));

    $this->validatorSchema['email'] = new sfValidatorPropelChoice(array(
        'model' => 'Collector',
        'column' => 'email',
    ));
    $this->validatorSchema['captcha'] = new IceValidatorCaptcha();

    $this->widgetSchema->setNameFormat('recover[%s]');
  }

}