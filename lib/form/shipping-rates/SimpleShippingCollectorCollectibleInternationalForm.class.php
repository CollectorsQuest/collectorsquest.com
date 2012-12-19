<?php

class SimpleShippingCollectorCollectibleInternationalForm extends SimpleShippingCollectorCollectibleForCountryForm
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

  protected function setupShippingTypeField($shipping_types = null)
  {
    parent::setupShippingTypeField($shipping_types);

    $this->setDefault(
      'shipping_type',
      ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING
    );
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
    ), array('data-placeholder' => 'Choose countries if applicable...'));

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
        ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING => 'No shipping',
        self::SHIPPING_TYPE_FREE_SHIPPING => 'Free shipping',
        ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE => 'Flat Rate',
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

    // if the previous code did not set "do not ship to" countries, and the current
    // object is not a collector AND it doesn't currenly have any shipping references
    // (ie, it is a collectible for which no shipping settings have ever been saved),
    // then populate the list from the related collector (where the user might have
    // set defaults)
    $do_not_ship_to = $this->getDefault('do_not_ship_to');
    if (
      empty($do_not_ship_to) &&
      !$this->related_object instanceof Collector &&
      !ShippingReferenceQuery::create()->filterByModelObject($this->related_object)->count()
    )
    {
      $this->setDefault('do_not_ship_to', ShippingReferenceQuery::create()
        ->filterByModelObject($this->related_object->getCollector())
        ->filterByCountryIso3166(array('US', 'ZZ'), Criteria::NOT_IN)
        ->filterByShippingType(ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING)
        ->select(array('CountryIso3166'))
        ->find()->getArrayCopy()
      );
    }
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
