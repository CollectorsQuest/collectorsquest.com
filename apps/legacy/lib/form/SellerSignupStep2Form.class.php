<?php

class SellerSignupStep2Form extends BaseFormPropel
{
  public function setup()
  {
//	unset($this['username'], $this['password'], $this['display_name'],$this['email'], $this['what_you_collect'], $this['what_you_sell']);

    $recaptcha = sfConfig::get('app_credentials_recaptcha');

    $years = array_combine(range(date('Y') - 100, date('Y')), range(date('Y') - 100, date('Y')));

    $countries = sfCultureInfo::getInstance('en')->getCountries();
    //Dirty hack but only solution currently
    $top = array(
      '' => '',
      'US' => $countries['US'],
      'GB' => $countries['GB'],
      'AU' => $countries['AU'],
    );

    foreach ($top as $key => $value)
    {
      unset($countries[$key]);
    }
    $countries = array_merge($top, $countries);

    // Set Widgets
    $this->setWidgets(array(
      'birthday' => new sfWidgetFormDate(array('years' => $years)),
      'gender' => new sfWidgetFormSelect(array('choices' => array('' => null, 'f' => 'Female', 'm' => 'Male'))),
      'zip_postal' => new sfWidgetFormInputText(),
      'country' => new sfWidgetFormChoice(array('choices' => $countries)),
      'website' => new sfWidgetFormInputText(),
      'company' => new sfWidgetFormInputText(),
//      'captcha'    => new sfWidgetFormReCaptcha(array('pubic_key' => $recaptcha['public_key']))
    ));

    // Set Validators
    $this->setValidators(array(
      'birthday' => new sfValidatorDate(array('required' => false)),
      'gender' => new sfValidatorChoice(array('choices' => array('f', 'm'), 'required' => false)),
      'zip_postal' => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'country' => new sfValidatorI18nChoiceCountry(array('required' => true)),
      'website' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'company' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
//      'captcha'    => new sfValidatorReCaptcha(array('private_key' => $recaptcha['private_key']))
    ));

    $this->widgetSchema->setNameFormat('sellerstep2[%s]');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collector';
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if ('US' == $taintedValues['country'])
    {
      $this->getValidator('zip_postal')->setOption('required', true);
    }

    parent::bind($taintedValues, $taintedFiles);
  }

}
