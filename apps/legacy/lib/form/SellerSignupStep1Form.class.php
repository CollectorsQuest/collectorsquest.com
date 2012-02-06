<?php

class SellerSignupStep1Form extends BaseFormPropel
{
  public function setup()
  {
    $recaptcha = sfConfig::get('app_credentials_recaptcha');

  	// Set Widgets
    $this->setWidgets(array(
      'username'       		=> new sfWidgetFormInputText(),
      'password'       		=> new sfWidgetFormInputPassword(),
      'password_again' 		=> new sfWidgetFormInputPassword(),
      'display_name'   		=> new sfWidgetFormInputText(),
      'email'          		=> new sfWidgetFormInputText(),

      'what_you_collect'	=> new sfWidgetFormInputText(),
      'what_you_sell'		=> new sfWidgetFormInputText(),

//      'captcha'    => new sfWidgetFormReCaptcha(array('pubic_key' => $recaptcha['public_key']))
    ));

	  // Set Validators
    $this->setValidators(array(
      'username'       => new sfValidatorString(array('max_length' => 50, 'required' => true), array('required' => 'Please enter username')),
      'password'       => new sfValidatorString(array('min_length' => 6, 'max_length' => 50, 'required' => true),
	  											array('required' => 'Please enter password',
													  'min_length'	=> 'Password must be min 6 and max 50 characters long',
													  'max_length'	=> 'Password must be min 6 and max 50 characters long'
													)),
      'password_again' => new sfValidatorPass(),
      'display_name'   => new sfValidatorString(array('max_length' => 50, 'required' => true), array('required' => 'Please enter display name')),
      'email'          => new sfValidatorEmail(array('required' => true), array('required' => 'Please enter email')),

      'what_you_collect'	=> new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'what_you_sell'   	=> new sfValidatorString(array('max_length' => 255, 'required' => true), array('required' => 'Please enter what you sell')),

//      'captcha'    => new sfValidatorReCaptcha(array('private_key' => $recaptcha['private_key']))
    ));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorPropelUnique(
        array('model' => 'Collector', 'column' => 'username'),
        array('invalid' => 'This username is already taken, please choose another one!')
      ),
      new sfValidatorPropelUnique(
        array('model' => 'Collector', 'column' => 'email'),
        array('invalid' => 'This email already has an account, did you forget your password?')
      ),
      new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        array('throw_global_error' => true),
        array('invalid' => 'The two passwords do not match, please enter them again!')
      )
    )));

    $this->widgetSchema->setNameFormat('sellerstep1[%s]');

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
