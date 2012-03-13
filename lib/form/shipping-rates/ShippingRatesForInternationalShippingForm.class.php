<?php

/**
 * ShippingRatesForInternationalShippingForm
 */
class ShippingRatesForInternationalShippingForm extends ShippingRatesForCountryForm
{

  public function configure()
  {
    parent::configure();

    $current_calculation_type = $this->getTaintedRequestValue('calculation_type',
      $this->getCalculationTypeForDefaults());
    if (ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING != $current_calculation_type)
    {
      $this->setupDoNotShipToField();
    }
  }


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

  public function setupDoNotShipToField()
  {
    $q = GeoCountryQuery::create()
      ->filterByIso3166($this->getParentObject()->getDomesticCountryCode(), Criteria::NOT_EQUAL);
    $this->widgetSchema['do_not_ship_to'] = new sfWidgetFormPropelChoice(array(
        'model' => 'GeoCountry',
        'multiple' => true,
        'key_method' => 'getIso3166',
        'criteria' => $q,
    ));
    $this->validatorSchema['do_not_ship_to'] = new sfValidatorPropelChoice(array(
        'model' => 'GeoCountry',
        'column' => 'iso3166',
        'multiple' => true,
        'required' => false,
    ));
  }

  public function getDefaults()
  {
    $do_not_ship_to = array(
        'do_not_ship_to' =>  ShippingRateQuery::createStubQueryForRelatedObject($this->getParentObject())
      ->filterByCalculationType(ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING)
      ->filterByCountryIso3166($this->getparentobject()->getDomesticCountryCode(), Criteria::NOT_EQUAL)
      ->select(array('CountryIso3166'))
      ->find()->getArrayCopy()
    );

    return array_merge(
      parent::getDefaults(),
      $do_not_ship_to
    );
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