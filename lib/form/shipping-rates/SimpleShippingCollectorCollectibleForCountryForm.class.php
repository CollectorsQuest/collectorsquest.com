<?php

class SimpleShippingCollectorCollectibleForCountryForm extends ShippingCollectorCollectibleForCountryForm
{

  // only used internally in this form as shipping references
  // do not have a "free shipping" option
  const SHIPPING_TYPE_FREE = 'free_shipping';

  public function configure()
  {
    parent::configure();

    $this->setupFlatRateAmountField();

    $this->mergePostValidator(
      new SimpleShippingCollectorCollectibleForCountryFormValidatorSchema(null));

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupShippingTypeField($shipping_types = null)
  {
    if (null === $shipping_types)
    {
      $shipping_types = self::getShippingTypeChoices();
    }

    $this->widgetSchema['shipping_type'] = new sfWidgetFormSelectRadio(array(
        'choices' => $shipping_types,
        'formatter' => array($this, 'radioInputFormatter'),
    ));

    $this->validatorSchema['shipping_type'] = new sfValidatorChoice(array(
        'choices' => array_keys($shipping_types),
    ));
  }

  protected function setupFlatRateAmountField()
  {
    $this->widgetSchema['flat_rate'] = new cqWidgetFormInputCentsToUsd(array(
        'show_zero_as_empty' => true,
    ));
    $this->validatorSchema['flat_rate'] = new cqValidatorUSDtoCents(array(
        'required' => false,
    ));

    if (!$this->isShippingTypeFlatRate())
    {
      $this->widgetSchema['flat_rate']->setAttributes(array(
          'class' => 'disabled',
          'disabled' => 'disabled',
      ));
    }
  }

  public function isShippingTypeFlatRate()
  {
    return ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE ==
      $this->getTaintedRequestValue('shipping_type',
        $this->getObject()->getShippingType());
  }

  protected static function getShippingTypeChoices()
  {
    return array(
        self::SHIPPING_TYPE_FREE => 'Free Shipping',
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
      $shipping_rate = $this->getObject()->getShippingRates()->getFirst();
      // if the shipping rate is free simply mark the shipping type as such
      // (this is a fake type, the actual field does not allow this value,
      // it's only used internally in this form)
      if ($shipping_rate->getIsFreeShipping())
      {
        $this->setDefault('shipping_type', self::SHIPPING_TYPE_FREE);
      }
      else
      {
        // if not then we repopulate the "flat_rate" field;
        // shipping_tye
        $this->setDefault('flat_rate', $shipping_rate->getFlatRateInCents());
      }
    }
    else if (!$this->isNew())
    {
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
    // type free shipping and set this record to calculated shipping
    if (self::SHIPPING_TYPE_FREE == $values['shipping_type'])
    {
      $values['shipping_type'] = ShippingReferencePeer::SHIPPING_TYPE_CALCULATED_SHIPPING;
      $shipping_rate = new ShippingRate();
      $shipping_rate->setIsFreeShipping(true);
      $shipping_rate->setShippingReference($this->getObject());
      // the shipping rate object will be saved when this form's object is saved
    } else
    if ($values['shipping_type'] == ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE)
    {
      $shipping_rate = new ShippingRate();
      $shipping_rate->setFlatRateInCents($values['flat_rate']);
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