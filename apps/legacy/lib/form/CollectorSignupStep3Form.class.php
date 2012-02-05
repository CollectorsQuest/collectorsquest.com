<?php

class CollectorSignupStep3Form extends BaseFormPropel
{
  public function setup()
  {
    $recaptcha = sfConfig::get('app_credentials_recaptcha');

    $years = array_combine(range(date('Y') - 100, date('Y')), range(date('Y') - 100, date('Y')));

    $this->setWidgets(array(
      'birthday'   => new sfWidgetFormDate(array('years' => $years)),
      'gender'     => new sfWidgetFormSelect(array('choices' => array('' => null, 'f' => 'Female', 'm' => 'Male'))),
      'zip_postal' => new sfWidgetFormInputText(),
      'country'    => new sfWidgetFormI18nSelectCountry(array('add_empty' => true, 'culture' => 'en')),
      'website'    => new sfWidgetFormInputText(),

//      'captcha'    => new sfWidgetFormReCaptcha(array('public_key' => $recaptcha['public_key']))
    ));

    $this->setValidators(array(
      'birthday'   => new sfValidatorDate(array('required' => false)),
      'gender'     => new sfValidatorChoice(array('choices' => array('f', 'm'), 'required' => false)),
      'zip_postal' => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'country'    => new sfValidatorI18nChoiceCountry(array('required' => true)),
      'website'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),

//      'captcha'    => new sfValidatorReCaptcha(array('private_key' => $recaptcha['private_key']))
    ));

    $this->widgetSchema->setNameFormat('collectorstep3[%s]');

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
