<?php


require 'lib/model/om/BaseCollectibleRate.php';


/**
 * Skeleton subclass for representing a row from the 'collectible_rates' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class CollectibleRate extends BaseCollectibleRate {

  public function postSave(PropelPDO $con = null)
  {
    /* @var $object Collectible */
    $object = $this->getCollectible();
    $c = new Criteria();
    $c->add(CollectibleRatePeer::DIMENSION, $this->getDimension());

    //Set average rates for dimensions
    $r = 0;
    /* @var $rates CollectibleRate[] */
    $rates = $object->getCollectibleRates($c);
    if (count($rates))
    {
      foreach ($rates as $rate)
      {
        $r = $r + $rate->getRate();
      }
      $object->setByName('average_'.$this->getDimension().'_rate', $r / count($rates), BasePeer::TYPE_FIELDNAME);
    }

    //Set average total rates
    $r = 0;
    foreach (CollectibleRatePeer::getDimensions() as $dimension => $label)
    {
      $r = $r + $object->getByName('average_'.$dimension.'_rate', BasePeer::TYPE_FIELDNAME);
    }
    $object->setAverageRate($r / count(CollectibleRatePeer::getDimensions()));

    $object->save();

    return parent::postSave($con);
  }

  public function getDimensionLabel()
  {
    $dimensions = CollectibleRatePeer::getDimensions();

    return $dimensions[$this->getDimension()];
  }

  public function getAverageRate()
  {
    /* @var $object Collectible */
    $object = $this->getCollectible();

    return $object->getByName('average_'.$this->getDimension().'_rate', BasePeer::TYPE_FIELDNAME);
  }

  public function getTotalRates()
  {
    /* @var $object Collectible */
    $object = $this->getCollectible();
    $c = new Criteria();
    $c->add(CollectibleRatePeer::DIMENSION, $this->getDimension());

    return $object->countCollectibleRates($c);
  }
}
