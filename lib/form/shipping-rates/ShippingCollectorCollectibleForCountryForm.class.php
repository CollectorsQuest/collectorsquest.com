<?php

/**
 * @method    ShippingReference getObject()
 */
class ShippingCollectorCollectibleForCountryForm extends ShippingReferenceForm
{
  /** @var Collector|Collectible */
  protected $related_object;
  protected $country_code;

  /**
   * @param     Collector|Collectible $related_object
   * @param     string $country_code
   * @param     array $options
   * @param     string $CSRFSecret
   */
  public function __construct(
    $related_object,
    $country_code,
    $tainted_request_values,
    $options = array(),
    $CSRFSecret = null
  ) {
    if (!in_array(get_class($related_object), array('Collector', 'Collection')))
    {
      throw new InvalidArgumentException(sprintf(
        'ShippingCollectorCollectibleForm exects a Collector or Collectible object,
         %s given',
        get_class($object)
      ));
    }

    $this->related_object = $related_object;
    $this->country_code = $country_code;
    $options['tainted_request_values'] = $tainted_request_values;

    // we need to set the object here because of sfFormPropel::__construct checks
    $this->object = $this->getOrCreateShippingReferenceFromRelatedObject();

    parent::__construct($this->object, $options, $CSRFSecret);
  }

  /**
   * Main form configuration
   */
  public function configure()
  {
    parent::configure();

    $this->setupShippingTypeField();
    $this->setupVisibleFieldsBasedOnShippingType();

    $this->widgetSchema->setNameFormat(
      'shipping_rates_'.strtolower($this->country_code).'[%s]'
    );

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->mergePostValidator(
      new ShippingCollectorCollectibleForCountryFormValidatorSchema(null));
  }

  protected function unsetFields()
  {
    unset ($this['id']);
    unset ($this['model']);
    unset ($this['model_id']);
    unset ($this['country_iso3166']);
  }

  protected function setupShippingTypeField($shipping_types = null)
  {
    if (null === $shipping_types)
    {
      $shipping_types = self::getShippingTypeChoices();
    }

    $this->widgetSchema['shipping_type'] = new sfWidgetFormChoice(array(
        'choices' => $shipping_types,
    ));

    $this->validatorSchema['shipping_type'] = new sfValidatorChoice(array(
        'choices' => array_keys($shipping_types),
    ));
  }

  protected static function getShippingTypeChoices()
  {
    return array(
        '' => '',
        ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE => 'Flat Rate: Same cost to all buyers',
        ShippingReferencePeer::SHIPPING_TYPE_CALCULATED_SHIPPING => 'Calculated: Cost varies by buyer location',
        ShippingReferencePeer::SHIPPING_TYPE_LOCAL_PICKUP_ONLY => 'Local Pickup: You offer only local pickup',
    );
  }

  protected function setupVisibleFieldsBasedOnShippingType()
  {
    switch ($this->getCurrentShippingType()):
      case (ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE):
        $form = new FlatShippingRateCollectionForm($this->getObject());
        $this->embedForm('shipping_rates', $form);
        break;

      case (ShippingReferencePeer::SHIPPING_TYPE_CALCULATED_SHIPPING):
        $form = new CalculatedShippingRateCollectionForm($this->getObject());
        $this->embedForm('shipping_rates', $form);
        break;

      case (ShippingReferencePeer::SHIPPING_TYPE_LOCAL_PICKUP_ONLY):
      case (ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING):
      default:
        // no additional form required
        break;

    endswitch;
  }

  protected function getCurrentShippingType()
  {
    return $this->getTaintedRequestValue('shipping_type',
      !$this->getObject()->isNew()
        ? $this->getObject()->getShippingType()
        : '');
  }

  protected function doSave($con = null)
  {

    // embedded forms
    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $this->getObject()->clearShippingRates();
    $this->updateObject();

    // this is Propel specific
    if(isset($this->getObject()->markForDeletion))
    {
      $this->getObject()->delete($con);
    }
    else
    {
      $this->getObject()->save($con);
    }

    $this->saveEmbeddedForms($con);
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $forms)
    {
      $shipping_rates_collection = $this->getValue('shipping_rates');
      $forms = $this->embeddedForms;
      foreach ($this->embeddedForms['shipping_rates'] as $name => $form)
      {
        if (!isset($shipping_rates_collection[$name]))
        {
          unset($forms['shipping_rates'][$name]);
        }
      }
    }

    return parent::saveEmbeddedForms($con, $forms);
  }

  /**
   * @return ShippingReference
   */
  protected function getOrCreateShippingReferenceFromRelatedObject()
  {
    if (!$this->object)
    {
      $this->object = $this->related_object
        ->getShippingReferenceForCountryCode($this->country_code);

      if (!$this->object)
      {
        $class = $this->getModelName();
        $this->object = new $class();
        $this->object->setCountryIso3166($this->country_code);
        call_user_func(
          array($this->object, 'setModelObject'),
          $this->related_object
        );
      }
    }

    return $this->object;
  }


  /**
   * Easy getter for values set in the tainted_request_values option,
   * which should be an associative array
   *
   * @param     string $value_name
   * @param     mixed $default
   * @return    mixed
   */
  protected function getTaintedRequestValue($value_name, $default = null)
  {
    $tainted_request_values = $this->getOption('tainted_request_values', array());

    if (isset($tainted_request_values[$value_name]))
    {
      return $tainted_request_values[$value_name];
    }
    else
    {
      return $default;
    }
  }

}
