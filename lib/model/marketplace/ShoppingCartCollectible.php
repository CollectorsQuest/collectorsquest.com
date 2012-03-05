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

  public function getCollectible(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCollectible($con);
  }

  public function getCollectibleId(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCollectibleId($con);
  }

  public function getName(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getName();
  }

  public function getCondition(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCondition();
  }

  public function getTotalPrice()
  {
    return $this->getPrice();
  }
}
