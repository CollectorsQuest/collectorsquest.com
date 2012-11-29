<?php

/**
 * Validates a state/region by country code and region name/id.
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
      $country_iso3166 = $values[$this->getOption('country_field')];
      $region = $values[$this->getOption('region_field')];

      /* @var $q iceModelGeoRegionQuery */
      $q = iceModelGeoRegionQuery::create()
        ->useiceModelGeoCountryQuery()
          ->filterByIso3166($country_iso3166)
        ->endUse();

      // Validate only if ve have states for country at DB
      if ($q->count() && !$q->filterById((int) $region)->_or()->filterByNameLatin((string) $region)->count())
      {
        throw new sfValidatorErrorSchema($this, array(
          $this->getOption('region_field') => new sfValidatorError($this, 'invalid'),
        ));
      }
    }

    // assume a required error has already been thrown, skip validation
    return $values;
  }
}
