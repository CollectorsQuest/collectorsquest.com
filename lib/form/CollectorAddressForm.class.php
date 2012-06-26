<?php

/**
 * CollectorAddress form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class CollectorAddressForm extends BaseCollectorAddressForm
{

  public function configure()
  {
    $this->setupZipPostcodeField();
    $this->setupStateRegionField();
    $this->setupCountryIso3166Field();
    $this->setupPhoneField();

    $this->validatorSchema['full_name']->setMessage('required',
      'Please enter your full name');
    $this->validatorSchema['address_line_1']->setMessage('required',
      'Please enter your street address');
    $this->validatorSchema['city']->setMessage('required',
      'Please enter your city');
    $this->validatorSchema['zip_postcode']->setMessage('required',
      'Please enter your postal code');

    $this->unsetFields();
  }

  protected function setupZipPostcodeField()
  {
    if ($this->widgetSchema['zip_postcode'])
    {
      $this->widgetSchema['zip_postcode']->setLabel('ZIP / Postal Code');
    }
  }

  protected function setupStateRegionField()
  {
    if ($this->widgetSchema['state_region'])
    {
      $this->widgetSchema['state_region']->setLabel('State / Province');
    }
  }

  protected function setupCountryIso3166Field()
  {
    if ($this->widgetSchema['country_iso3166'])
    {
      $this->widgetSchema['country_iso3166']
        ->setLabel('Country')->setOption('add_empty', false);
      $this->validatorSchema['country_iso3166']->setMessage('required',
        'Please supply a country for this address.');
    }
  }

  protected function setupPhoneField()
  {
    if ($this->widgetSchema['phone'])
    {
      $this->widgetSchema['phone']->setLabel('Phone Number');
      $this->validatorSchema['phone']->setMessage('required',
        'Please supply a phone number so we can call if
         there are any problems using this address.'
      );
    }
  }

}
