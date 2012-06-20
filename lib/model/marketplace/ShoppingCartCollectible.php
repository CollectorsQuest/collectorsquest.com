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

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function getTaxAmount($return = 'float')
  {
    $amount = parent::getTaxAmount();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function getShippingFeeAmount($return = 'float')
  {
    $amount = parent::getShippingFeeAmount();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  /**
   * Automatically update the shipping fee amount based either on a new country code
   * or on the currently set shipping country iso 3166
   *
   * @param     string|null $country_code
   * @return    ShoppingCartCollectible
   *
   * @throws    Exception if the shipping reference for the selected country is of type No Shipping
   */
  public function updateShippingFeeAmountFromCountryCode($country_code = null)
  {
    if (null !== $country_code)
    {
      $this->setShippingCountryIso3166($country_code);
    }

    $shipping_amount = $this->getCollectibleForSale()
      ->getShippingAmountForCountry($this->getShippingCountryIso3166(), 'integer');

    if (false === $shipping_amount)
    {
      throw new Exception('Cannot automatically update shipping fee amount for shopping cart collectible %d and country %s:
        The related shipping reference was of type No Shipping', $this->getCollectibleId(), $this->getShippingCountryIso3166());
    }

    $this->setShippingFeeAmount($shipping_amount);

    return $this;
  }

  /**
   * Pre save hoo
   *
   * @param     PropelPDO $con
   * @return    boolean
   */
  public function preSave(PropelPDO $con = null)
  {
    // if the shipping country iso 3166 has been changed, but no manual shipping fee
    // amount was entered automatically recalculate the shipping fee amount
    if (
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_COUNTRY_ISO3166) &&
      !$this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_FEE_AMOUNT)
    ) {
      $this->updateShippingFeeAmountFromCountryCode();
    }

    return parent::preSave($con);
  }

}
