<?php

require 'lib/model/marketplace/om/BaseShippingReferenceQuery.php';

class ShippingReferenceQuery extends BaseShippingReferenceQuery
{

  /**
   * Filter based on Collector
   *
   * @param     Collector|int $collector
   * @return    ShippingReferenceQuery
   */
  public function filterByCollector($collector)
  {
    if ($collector instanceof Collector)
    {
      $collector = $collector->getId();
    }

    return $this
      ->filterByModel('Collector')
      ->filterByModelId($collector);
  }

  /**
   * Filter based on Collectible
   *
   * @param  Collectible|int  $collectible
   * @return ShippingReferenceQuery
   */
  public function filterByCollectible($collectible)
  {
    if ($collectible instanceof Collectible)
    {
      $collectible = $collectible->getId();
    }

    return $this
      ->filterByModel('Collectible')
      ->filterByModelId($collectible);
  }

  /**
   * @param     Collector|Collectible $object
   *
   * @throws    Exception
   * @return    ShippingReferenceQuery
   */
  public function filterByModelObject($object)
  {
    if (!in_array(get_class($object), array('Collector', 'Collectible')))
    {
      throw new Exception(sprintf(
        'You can only filter on Collector or Collectible object. You tried to filter on "%s".',
        is_object($object) ? get_class($object) : gettype($object)
      ));
    }

    return $this
      ->filterByModel(get_class($object))
      ->filterByModelId($object->getPrimaryKey());
  }

}
