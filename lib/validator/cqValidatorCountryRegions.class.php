<?php

/**
 * Validates a state/region by country code.
 *
 */
class cqValidatorCountryRegions extends sfValidatorBase
{
  /**
   * Configures the user validator.
   *
   * Available options:
   *
   *  * country_field     Field name of country field (country by default)
   *  * region_field      Field name of region/state field (region by default)
   *
   * @see sfValidatorBase
   */
  public function configure($options = array(), $messages = array())
  {
    $this->addOption('country_field', 'country');
    $this->addOption('region_field', 'region');

  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    // only validate if country and region are both present
    if (isset($values[$this->getOption('country_field')]) && isset($values[$this->getOption('region_field')]))
    {
      $country = $values[$this->getOption('country_field')];
      $region = $values[$this->getOption('region_field')];

      $states = $this->getStatesForCountry($country);
      // Validate only if ve have states for country at DB
      if (count($states))
      {
        if (!in_array($region, $states))
        {
          throw new sfValidatorErrorSchema($this, array(
            $this->getOption('region_field') => new sfValidatorError($this, 'invalid'),
          ));
        }
      }
    }

    // assume a required error has already been thrown, skip validation
    return $values;
  }

  /**
   * Get list of region by country iso3166
   *
   * @param $country_iso3166
   * @return array
   */
  private function getStatesForCountry($country_iso3166)
  {
    $states = iceModelGeoRegionQuery::create()
      ->orderByNameLatin()
      ->useiceModelGeoCountryQuery()
        ->filterByIso3166($country_iso3166)
      ->endUse()
      ->select(array('Id', 'NameLatin'))
      ->find()
      ->toKeyValue('Id', 'NameLatin');

    return $states;
  }
}
