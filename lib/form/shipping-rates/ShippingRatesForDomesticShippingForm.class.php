<?php

/**
 * ShippingRatesForDomesticShippingForm
 */
class ShippingRatesForDomesticShippingForm extends ShippingRatesForCountryForm
{

  /**
   * For domestic shipping, a country widget should not be displayed to the user
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

    // shipping must always be selected for domestic
    unset($calculation_types[ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING]);

    parent::setupCalculationTypeField($calculation_types);
  }

  /**
   * Return the country code to be used in the defaults
   * For domestic form we need to get the coutry code from the parent object
   *
   * @return    string
   */
  protected function getCountryCodeForDefaults()
  {
    return $this->getParentObject()->getDomesticCountryCode();
  }

}

