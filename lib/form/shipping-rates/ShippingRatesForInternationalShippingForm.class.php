<?php

/**
 * ShippingRatesForInternationalShippingForm
 */
class ShippingRatesForInternationalShippingForm extends ShippingRatesForCountryForm
{

  /**
   * For international shipping, a country widget should not be displayed to the user
   */
  protected function setupCountryIso3166Field()
  {
    parent::setupCountryIso3166Field();

    $this->widgetSchema['country_iso3166'] = new sfWidgetFormInputHidden();
  }

  protected function setupCalculationTypeField($calculation_types = null)
  {
    if (null === $calculation_types)
    {
      $calculation_types = self::getCalculationTypeValues();
    }

    // local pickup is not an option for international shipping
    unset($calculation_types[ShippingRatePeer::CALCULATION_TYPE_LOCAL_PICKUP]);

    parent::setupCalculationTypeField($calculation_types);
  }

  /**
   * Return the country code to be used in the defaults
   * For international shipping we hardcode ZZ
   *
   * @return    string
   */
  protected function getCountryCodeForDefaults()
  {
    return 'ZZ';
  }

  protected function getCalculationTypeForDefaults()
  {
    $calculation_type = parent::getCalculationTypeForDefaults();

    if ('' == $calculation_type)
    {
      $calculation_type = ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING;
    }

    return $calculation_type;
  }

}