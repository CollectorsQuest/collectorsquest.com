<?php

require 'lib/model/marketplace/om/BaseCollectibleForSale.php';

class CollectibleForSale extends BaseCollectibleForSale
{
  /**
   * Pre save hooks
   *
   * @param     PropelPDO $con
   * @return    boolean
   */
  public function preSave(PropelPDO $con = null)
  {
    // we check if the IS_READY field was modified, and if the MARKED_FOR_SALE_AT
    // field was not manually set and IS_READY is true we set MARKED_FOR_SALE_AT
    // to the current time
    if (
      $this->isColumnModified(CollectibleForSalePeer::IS_READY) &&
      !$this->isColumnModified(CollectibleForSalePeer::MARKED_FOR_SALE_AT) &&
      $this->getIsReady()
    ) {
      $this->setMarkedForSaleAt(time());
    }

    return parent::preSave($con);
  }

  /**
   * Proxy method to Collectible::getName()
   *
   * @param  null|\PropelPDO  $con
   * @return string
   */
  public function getName(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getName();
  }

  /**
   * Proxy method to Collectible::getDescription()
   *
   * @param  string   $type
   * @param  integer  $limit
   * @param  null|PropelPDO  $con
   *
   * @return string
   */
  public function getDescription($type = 'html', $limit = 0, PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getDescription($type, $limit);
  }

  /**
   * @depricated
   * @return float
   */
  public function getPrice()
  {
    return (float) bcdiv((string) $this->getPriceAmount(), 100, 2);
  }

  public function setPrice($v)
  {
    $this->setPriceAmount((int) bcmul($v, 100));
  }

  /**
   * Check if shipping is free for this collectible for sale
   *
   * If no country code is provided, all shipping references are checked
   *
   * @param     string $country_code
   * @return    boolean
   */
  public function isShippingFree($country_code = null)
  {
    if (null !== $country_code)
    {
      return 0 === $this->getShippingAmountForCountry($country_code);
    }

    $shipping_references = $this->getCollectible()
      ->getShippingReferencesByCountryCode();
    if (empty($shipping_references))
    {
      return true;
    }

    /** @var $shipping_references ShippingReference[] */
    foreach ($shipping_references as $shipping_reference)
    {
      if (!$shipping_reference->isSimpleFreeShipping())
      {
        return false;
      }
    }

    return true;
  }

  /**
   * Return a simple shipping amount for a country
   *
   * @param     boolean|string $country_code
   * @param     string $return "float|integer"
   * @param     null|PropelPDO $con
   *
   * @return    mixed A float amount in USD, 0 if free shipping or FALSE if no shipping
   */
  public function getShippingAmountForCountry($country_code = false, $return = 'float',  PropelPDO $con = null)
  {
    if (false === $country_code)
    {
      $country_code = 'US';
    }

    $shipping_refenrence = $this->getCollectible($con)
      ->getShippingReferenceForCountryCode($country_code, $con);

    if (!$shipping_refenrence)
    {
      // if no shipping reference, assume free shipping
      return 0;
    }

    return $shipping_refenrence->getSimpleShippingAmount($return);
  }

  /**
   * Proxy method to Collectible::getCollector()
   *
   * @param  null|PropelPDO  $con
   * @return Collector
   */
  public function getCollector(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollector($con);
  }

  public function getCollectorId(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollectorId();
  }

  /**
   * Proxy method to Collectible::getCollection()
   *
   * @param  null|PropelPDO  $con
   * @return Collection|CollectionDropbox
   */
  public function getCollection(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollection($con);
  }

  public function isForSale()
  {
    if (!$this->getIsReady()) {
      return false;
    }
    else if ($this->getIsSold()) {
      return false;
    }
    else if ($this->getQuantity() === 0) {
      return false;
    }
    else if ($this->getPriceAmount() === 0) {
      return false;
    }

    return true;
  }

  /**
   * Is there an active credit available for this Collectible?
   *
   * @param     PropelPDO $con
   * @return    boolean
   */
  public function hasActiveCredit(PropelPDO $con = null)
  {
    return !!PackageTransactionCreditQuery::create()
      ->filterByCollectibleId($this->getCollectibleId())
      ->notExpired()
      ->count($con);
  }

  /**
   * Get the oldest active Package Transaction Credit for the related collectible
   *
   * @param     PropelPDO $con
   * @return    PackageTransactionCredit
   */
  public function getActiveCredit(PropelPDO $con = null)
  {
    return PackageTransactionCreditQuery::create()
      ->filterByCollectibleId($this->getCollectibleId())
      ->notExpired()
      ->orderByExpiryDate(Criteria::ASC)
      ->findOne($con);
  }

  public function getShoppingOrder()
  {
    return ShoppingOrderQuery::create()
      ->findOneByCollectibleId($this->getCollectibleId());
  }

}
