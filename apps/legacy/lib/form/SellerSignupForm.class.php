<?php

class SellerSignupForm extends BaseFormPropel
{
  public function setup()
  {
    $recaptcha = sfConfig::get('app_credentials_recaptcha');

    $years = array_combine(range(date('Y') - 100, date('Y')), range(date('Y') - 100, date('Y')));

    $this->setWidgets(array(
      'username'       => new sfWidgetFormInputText(),
      'password'       => new sfWidgetFormInputPassword(),
      'password_again' => new sfWidgetFormInputPassword(),
      'display_name'   => new sfWidgetFormInputText(),
      'email'          => new sfWidgetFormInputText(),

      'birthday'   => new sfWidgetFormDate(array('years' => $years)),
      'gender'     => new sfWidgetFormSelect(array('choices' => array('' => null, 'f' => 'Female', 'm' => 'Male'))),
      'zip_postal' => new sfWidgetFormInputText(),
      'country'    => new sfWidgetFormI18nSelectCountry(array('add_empty' => true, 'culture' => 'en')),
      'website'    => new sfWidgetFormInputText(),

//      'captcha'    => new sfWidgetFormReCaptcha(array('public_key' => $recaptcha['public_key']))
    ));

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

      'birthday'   => new sfValidatorDate(array('required' => false)),
      'gender'     => new sfValidatorChoice(array('choices' => array('f', 'm'), 'required' => false)),
      'zip_postal' => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'country'    => new sfValidatorI18nChoiceCountry(array('required' => false)),
      'website'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),

//      'captcha'    => new sfValidatorReCaptcha(array('private_key' => $recaptcha['private_key']))
    ));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorPropelUnique(
        array('model' => 'Collector', 'column' => 'username'),
        array('invalid' => 'This username is already taken, please choose another one!')
      ),
      new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        array('throw_global_error' => true),
        array('invalid' => 'The two passwords do not match, please enter them again!')
      )
    )));

    $this->widgetSchema->setNameFormat('seller[%s]');

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
