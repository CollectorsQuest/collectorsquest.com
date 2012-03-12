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
    return $this->getParentObjectCountryCode();
  }

  /**
   * Get the country code of the parent object, regardless if it is a
   * Collector or a Collectible
   *
   * @return    string
   */
  protected function getParentObjectCountryCode()
  {
    $parent_object = $this->getParentObject();
    if ($parent_object instanceof Collector)
    {
      return $parent_object->getProfile()->getCountryIso3166();
    }

    if ($parent_object instanceof Collectible)
    {
      return $parent_object->getCollector()->getProfile()->getCountryIso3166();
    }
  }

}

