<?php

class ShippingAddressForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'full_name'       => new sfWidgetFormInputText(),
      'address_line_1'  => new sfWidgetFormInputText(),
      'address_line_2'  => new sfWidgetFormInputText(),
      'city'            => new sfWidgetFormInputText(),
      'state_region'    => new sfWidgetFormInputText(),
      'zip_postcode'    => new sfWidgetFormInputText(),
      'country_iso3166' => new cqWidgetFormI18nChoiceCountry(array('add_empty' => false)),
    ));

    $this->setValidators(array(
      'full_name'       => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'address_line_1'  => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'address_line_2'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'            => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'state_region'    => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'zip_postcode'    => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'country_iso3166' => new sfValidatorPropelChoice(array('model' => 'GeoCountry', 'column' => 'iso3166', 'required' => true)),
    ));

    $this->widgetSchema->setNameFormat('shipping_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function configure()
  {
    $this->widgetSchema['state_region']->setLabel('State / Province');
    $this->widgetSchema['zip_postcode']->setLabel('ZIP / Postal Code');
    $this->widgetSchema['country_iso3166']
      ->setLabel('Country')->setOption('add_empty', true);

    $this->validatorSchema['full_name']->setMessage('required',
      'Please supply full name for this address.');
    $this->validatorSchema['address_line_1']->setMessage('required',
      'At least one address line must be supplied.');
    $this->validatorSchema['city']->setMessage('required',
      'You must supply a city for this address.');
    $this->validatorSchema['country_iso3166']->setMessage('required',
      'Please supply a country for this address.');
  }

}
