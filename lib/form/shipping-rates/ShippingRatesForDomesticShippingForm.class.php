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
    $calculation_types = ShippingRatePeer::getValueSet(ShippingRatePeer::CALCULATION_TYPE);

    unset($calculation_types[array_search(
      ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING,
      $calculation_types)]);

    parent::setupCalculationTypeField($calculation_types);
  }

  /**
   * The domestic shipping rates form is separate from the per-country shipping
   * form, so we give it a special name
   *
   * @return    string
   */
  public function getNameForEmbedding()
  {
    return 'country_domestic';
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

  /**
   * Setup all embedded forms
   */
  protected function setupEmbeddedForms()
  {
    // if no existing shipping rates were embedded
    if (!$this->embedExistingShippingRates())
    {
      // we need to create a new empty one
      $embedded_form_class_name = $this->getEmbeddedFormClassForParentObject(
        $this->getParentObject());

      // create a new ShippingRate derived object
      $shipping_rate_class = $this->getShippingRateDerivedClassForParentObject(
        $this->getParentObject());
      $shipping_rate = new $shipping_rate_class();

      // set its defaults
      /* @var $shipping_rate ShippingRate */
      $shipping_rate->setCountryIso3166($this->getCountryCodeForDefaults());
      $related_object_setter_method = $this->getSetterMethodForEmbeddedObject(
        $this->getParentObject());
      $shipping_rate->$related_object_setter_method($this->getParentObject());

      // add the new form
      $form = $this->getNewShippingRateFormForEmbedding($shipping_rate);
      $this->embedForm('new_shipping_rate', $form);
    }
  }

}

