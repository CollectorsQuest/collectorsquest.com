<?php

class ShippingCollectorCollectibleInternationalForm extends ShippingCollectorCollectibleForCountryForm
{

  public function __construct(
    $related_object,
    $tainted_request_values,
    $options = array(),
    $CSRFSecret = null
  ) {
    parent::__construct(
      $related_object,
      'ZZ',
      $tainted_request_values,
      $options,
      $CSRFSecret
    );
  }

  public function configure()
  {
    parent::configure();

    $this->setupDoNotShipToField();
  }

  public function setupDoNotShipToField()
  {
    $q = iceModelGeoCountryQuery::create()
      ->filterByIso3166('US', Criteria::NOT_EQUAL);
    $this->widgetSchema['do_not_ship_to'] = new sfWidgetFormPropelChoice(array(
        'model' => 'iceModelGeoCountry',
        'multiple' => true,
        'key_method' => 'getIso3166',
        'criteria' => $q,
    ));
    $this->validatorSchema['do_not_ship_to'] = new sfValidatorPropelChoice(array(
        'model' => 'iceModelGeoCountry',
        'column' => 'iso3166',
        'multiple' => true,
        'required' => false,
    ));
  }

  protected static function getShippingTypeChoices()
  {
    return array(
        ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING => 'No shipping: You do not ship internationally',
        ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE => 'Flat Rate: Same cost to all buyers',
        ShippingReferencePeer::SHIPPING_TYPE_CALCULATED_SHIPPING => 'Calculated: Cost varies by buyer location',
    );
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveInternationalDoNotShipToRecords($con);
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefault('do_not_ship_to', ShippingReferenceQuery::create()
      ->filterByModelObject($this->related_object)
      ->filterByCountryIso3166(array('US', 'ZZ'), Criteria::NOT_IN)
      ->filterByShippingType(ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING)
      ->select(array('CountryIso3166'))
      ->find()->getArrayCopy()
    );
  }

  protected function saveInternationalDoNotShipToRecords($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    // delete previous "do not ship to" records
    ShippingReferenceQuery::create()
      ->filterByModelObject($this->related_object)
      ->filterByCountryIso3166(array('US', 'ZZ'), Criteria::NOT_IN)
      ->delete($con);

   $values = $this->getValues();

   if (isset($values['do_not_ship_to']) && is_array($values['do_not_ship_to']))
   {
     foreach ($values['do_not_ship_to'] as $country_code)
     {
       $shipping_reference = new ShippingReference();
       $shipping_reference->setModelObject($this->related_object);
       $shipping_reference->setShippingType(ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING);
       $shipping_reference->setCountryIso3166($country_code);
       $shipping_reference->save($con);
     }
   }
  }

}