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
    return $this->getGeoCountry()
      ? $this->getGeoCountry()->getName()
      : '';
  }

}
