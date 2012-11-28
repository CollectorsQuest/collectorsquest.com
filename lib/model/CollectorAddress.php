<?php

require 'lib/model/om/BaseCollectorAddress.php';

class CollectorAddress extends BaseCollectorAddress
{

  /**
   * Return the full name of the collector address' country
   *
   * @return    string
   */
  public function getCountryName()
  {
    if (( $country = $this->geticeModelGeoCountry() ))
    {
      return $country->getName();
    }

    return null;
  }

}
