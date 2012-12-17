<?php

class SimpleShippingCollectorCollectibleForCountryForm extends ShippingCollectorCollectibleForCountryForm
{

  // only used internally in this form as shipping references
  // do not have a "free shipping" option
  const SHIPPING_TYPE_FREE_SHIPPING = 'free_shipping';

  public function configure()
  {
    parent::configure();

    $this->setupFlatRateAmountField(
      $disabled = $this->getCurrentShippingType() != ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE
    );

    $this->mergePostValidator(
      new SimpleShippingCollectorCollectibleForCountryFormValidatorSchema(null));

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupShippingTypeField($shipping_types = null)
  {
    if (null === $shipping_types)
    {
      $shipping_types = static::getShippingTypeChoices();
    }

    $this->widgetSchema['shipping_type'] = new sfWidgetFormSelectRadio(array(
        'choices' => $shipping_types,
        'formatter' => array($this, 'radioInputFormatter'),
    ));

    $this->validatorSchema['shipping_type'] = new sfValidatorChoice(array(
        'choices' => array_keys($shipping_types),
    ));
  }

  protected function setupFlatRateAmountField($disabled = false)
  {
    $this->widgetSchema['flat_rate'] = new sfWidgetFormInput();
    $this->validatorSchema['flat_rate'] = new cqValidatorPrice(array(
        'required' => false, 'min' => 0.01
    ));

    if ($disabled)
    {
      $this->widgetSchema['flat_rate']->setAttributes(array(
          'class' => 'disabled',
          'disabled' => 'disabled',
      ));
    }
  }

  /**
   * @return    ShippingReferencePeer::SHIPPING_TYPE enum value or SimpleShippingCollectorCollectibleForCountryForm::SHIPPING_TYPE_FREE_SHIPPING
   */
  public function getCurrentShippingType()
  {
    $is_free_shipping = self::SHIPPING_TYPE_FREE_SHIPPING == $this->getTaintedRequestValue('shipping_type',
       $this->getObject()->getShippingRates()->count() == 1 &&
       $this->getObject()->getShippingRates()->getFirst()->getIsFreeShipping()
         ? self::SHIPPING_TYPE_FREE_SHIPPING
         : ''
    );

    return $is_free_shipping
      ? self::SHIPPING_TYPE_FREE_SHIPPING
      : parent::getCurrentShippingType();
  }

  protected static function getShippingTypeChoices()
  {
    return array(
        ShippingReferencePeer::SHIPPING_TYPE_LOCAL_PICKUP_ONLY => 'Local Pickup Only',
        self::SHIPPING_TYPE_FREE_SHIPPING => 'Free Shipping',
        ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE => 'Flat Rate',
    );
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    // we expect to have a single shipping rate saved to the DB in order to
    // repopulate this form
    if (1 == $this->getObject()->getShippingRates()->count())
    {
      /* @var $shipping_rate ShippingRate */
      $shipping_rate = $this->getObject()->getShippingRates()->getFirst();
      // if the shipping rate is free simply mark the shipping type as such
      // (this is a fake type, the actual field does not allow this value,
      // it's only used internally in this form)
      if ($shipping_rate->getIsFreeShipping())
      {
        $this->setDefault('shipping_type', self::SHIPPING_TYPE_FREE_SHIPPING);
      }
      // and if we have a flat rate, then we need to populate that field
      elseif ($shipping_rate->getFlatRateInCents())
      {
        $this->setDefault('flat_rate', $shipping_rate->getFlatRateInUSD());
      }
    }
    else if (
      !$this->isNew() &&
      !ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $this->getObject()->getShippingType()
    ) {
      throw new Exception('SimpleShippingCollectorCollectibleForCountryForm expects
        an object with a singular shipping rate for a country,
        the provided object had '.$this->getObject()->getShippingRates()->count());
    }
  }

  public function doUpdateObject($values)
  {
    // remove all previous shipping rate objects related to this shipping reference
    ShippingRateQuery::create()
      ->filterByShippingReference($this->getObject())
      ->delete();

    // we have free shipping, save a related ShippingRate object of
    // type free shipping and set this record to flat rate shipping
    if (self::SHIPPING_TYPE_FREE_SHIPPING == $values['shipping_type'])
    {
      $values['shipping_type'] = ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE;
      $shipping_rate = new ShippingRate();
      $shipping_rate->setIsFreeShipping(true);
      $shipping_rate->setShippingReference($this->getObject());
      // the shipping rate object will be saved when this form's object is saved
    }
    elseif ($values['shipping_type'] == ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE)
    {
      $shipping_rate = new ShippingRate();
      $shipping_rate->setFlatRateInUSD($values['flat_rate']);
      $shipping_rate->setShippingReference($this->getObject());
    }

    parent::doUpdateObject($values);
  }

  protected function setupVisibleFieldsBasedOnShippingType()
  {
    // do nothing
  }

  public function radioInputFormatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $rows[] = $widget->renderContentTag('label', $input['input'].strip_tags($input['label']), array('class' => 'radio'));
    }

    return !$rows ? '' : $widget->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }

}
