<?php

require 'lib/model/marketplace/om/BaseShoppingCartCollectible.php';

class ShoppingCartCollectible extends BaseShoppingCartCollectible
{
  public function getCollector(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCollector($con);
  }

  public function getCollectorId(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCollectorId($con);
  }

  public function getCollectibleForSale(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollectibleForSale($con);
  }

  public function getName(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getName();
  }

  public function getDescription(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getDescription('stripped', 85);
  }

  public function getCondition(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCondition();
  }

  public function getTotalPrice()
  {
    return $this->getPriceAmount();
  }

  /**
   * @param integer|float|double $v
   */
  public function setPriceAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 2), 100);
    }

    parent::setPriceAmount($v);
  }

  /**
   * @param integer|float|double $v
   */
  public function setTaxAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 2), 100);
    }

    parent::setTaxAmount($v);
  }

  /**
   * @param integer|float|double $v
   */
  public function setShippingFeeAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 2), 100);
    }

    parent::setShippingFeeAmount($v);
  }

  public function getPriceAmount($return = 'float')
  {
    $amount = parent::getPriceAmount();
    return ($return === 'integer') ? $amount : $amount / 100;
  }

  public function getTaxAmount($return = 'float')
  {
    $amount = parent::getTaxAmount();
    return ($return === 'integer') ? $amount : $amount / 100;
  }

  public function getShippingFeeAmount($return = 'float')
  {
    $amount = parent::getShippingFeeAmount();
    return ($return === 'integer') ? $amount : $amount / 100;
  }

}
