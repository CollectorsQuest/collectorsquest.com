<?php


require 'lib/model/marketplace/om/BaseShippingReference.php';


/**
 * Skeleton subclass for representing a row from the 'shipping_reference' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
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

    return $this;
  }

  /**
   * Return a simple value for the shipping reference amount
   *
   * @return    mixed A float if shipping amount set, 0 for free shipping and FALSE for No shipping
   * @throws    Exception when the shipping refenrence does not conform to the expected simple format
   */
  public function getSimpleShippingAmount()
  {
    if (ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $this->getShippingType())
    {
      return false;
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
        return $shipping_rate->getFlatRateInUSD();
      }
    }
    else
    {
      throw new Exception('ShippingReference::getSimpleShippingAmount() supports only no shipping or flat rate shipping');
    }
  }

}
