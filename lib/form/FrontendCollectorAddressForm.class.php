<?php

class FrontendCollectorAddressForm extends CollectorAddressForm
{

  public function configure()
  {
    parent::configure();

    unset ($this['id']);
    unset ($this['collector_id']);
    unset ($this['is_primary']);
  }

  public function getDefaults()
  {
    return array_merge(array(
        'country_iso3166' => $this->getCountryCodeFromCollector() ?: 'US',
    ),parent::getDefaults());
  }

  protected function getCountryCodeFromCollector()
  {
    if (( $collector = $this->getObject()->getCollector() ))
    {
      if (( $profile = $collector->getProfile() ))
      {
        return $profile->getCountryIso3166();
      }
    }

    return null;
  }

}