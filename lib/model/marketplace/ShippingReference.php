<?php

require 'lib/model/marketplace/om/BaseShippingReference.php';

class ShippingReference extends BaseShippingReference
{
  /** @var Collector|Collectible */
  protected $model_object;

  /**
   * Try to retrieve the model object tied to this reference
   *
   * @return    Collector|Collectible
   */
  public function getModelObject()
  {
    if (null === $this->model_object && $this->getModel())
    {
      $this->model_object = call_user_func(
        array($this->getModel() . 'Peer', 'retrieveByPk'), $this->getModelId());
    }

    return $this->model_object;
  }

  /**
   * Proxy get geo country name (with custom name for "Worldwide")
   *
   * @param     string $international_name
   * @return    string
   */
  public function getCountryName($international_name = 'Everywhere Else')
  {
    if ('ZZ' == $this->getCountryIso3166())
    {
      return $international_name;
    }
    else
    {
      return $this->geticeModelGeoCountry()->getName();
    }
  }

  /**
   * Set the related model object (Collector|Collectible)
   *
   * @param     Collector|Collectible $object
   * @return    ShippingReference
   */
  public function setModelObject(BaseObject $object)
  {
    if (!in_array(get_class($object), array('Collector', 'Collectible')))
    {
      throw new Exception(sprintf(
        'You can only add a Collector or Collectible object to a ShippingReference. You tried to add "%s".',
        is_object($object) ? get_class($object) : gettype($object)
      ));
    }

    $this->setModel(get_class($object));
    $this->setModelId($object->getPrimaryKey());
    $this->model_object = $object;

    return $this;
  }

  /**
   * Return a simple value for the shipping reference amount
   *
   * @param     string $return "float|integer"
   *
   * @return    mixed A float if shipping amount set, 0 for free shipping, FALSE for No shipping and NULL for Local Pickup Only
   * @throws    Exception when the shipping refenrence does not conform to the expected simple format
   */
  public function getSimpleShippingAmount($return = 'float')
  {
    if (ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $this->getShippingType())
    {
      return false;
    }

    if (ShippingReferencePeer::SHIPPING_TYPE_LOCAL_PICKUP_ONLY == $this->getShippingType())
    {
      return null;
    }

    if (ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE == $this->getShippingType())
    {
      if (1 != $this->getShippingRates()->count())
      {
        throw new Exception('ShippingReference::getSimpleShippingAmount() expects only one related ShippingRate');
      }

      $shipping_rate =  $this->getShippingRates()->getFirst();

      if ($shipping_rate->getIsFreeShipping())
      {
        return 0;
      }
      else
      {
        return 'integer' === $return
          ? $shipping_rate->getFlatRateInCents()
          : $shipping_rate->getFlatRateInUSD();
      }
    }
    else
    {
      throw new Exception('ShippingReference::getSimpleShippingAmount() supports only no shipping or flat rate shipping');
    }
  }

  /**
   * Check if we have only a single realted shipping rate of type
   * free shipping
   *
   * @return boolean
   *
   * @throws Exception when the simple shipping constraints are not followed
   */
  public function isSimpleFreeShipping()
  {
    if (ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE == $this->getShippingType())
    {
      if (1 != $this->getShippingRates()->count())
      {
        throw new Exception('ShippingReference::isSimpleFreeShipping() expects only one related ShippingRate');
      }

      return $shipping_rate = $this->getShippingRates()->getFirst()
        ->getIsFreeShipping();
    }
  }

}
