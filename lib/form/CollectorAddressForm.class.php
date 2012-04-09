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
    $this->widgetSchema['state_region']->setLabel('State/Province/Region');
    $this->widgetSchema['zip_postcode']->setLabel('ZIP');
    $this->widgetSchema['country_iso3166']
      ->setLabel('Country')->setOption('add_empty', true);
    $this->widgetSchema['phone']->setLabel('Phone Number');

    $this->validatorSchema['full_name']->setMessage('required',
      'Please supply a name for this address.');
    $this->validatorSchema['address_line_1']->setMessage('required',
      'At least one address line must be supplied.');
    $this->validatorSchema['city']->setMessage('required',
      'You must supply a city for this address.');
    $this->validatorSchema['country_iso3166']->setMessage('required',
      'Please supply a country for this address.');
    $this->validatorSchema['phone']->setMessage('required',
      'Please supply a phone number so we can call if there are any problems using this address.');
  }

}
