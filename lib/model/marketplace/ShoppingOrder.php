<?php

require 'lib/model/marketplace/om/BaseShoppingOrder.php';

class ShoppingOrder extends BaseShoppingOrder
{
  public function postSave(PropelPDO $con = null)
  {
    parent::postSave($con);

    if (!$this->getUuid())
    {
      $uuid = ShoppingOrderPeer::getUuidFromId($this->getId());

      $this->setUuid($uuid);
      $this->save();
    }
  }

  public function getCollectible()
  {
    return $this->getCollectibleForSale()->getCollectible();
  }

  public function getTotalAmount()
  {
    return $this->getCollectibleForSale()->getPrice();
  }

  public function getCurrency()
  {
    return 'USD';
  }

  public function getShippingAmount()
  {
    return '0';
  }

  public function getDescription()
  {
    return '';
  }
}
