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

}
