<?php

require 'lib/model/marketplace/om/BaseShoppingCartCollectible.php';

class ShoppingCartCollectible extends BaseShoppingCartCollectible
{
  /** @var ShippingReference */
  protected $aShippingReference;

  /**
   * Pre save hook
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

    // if the shipping country iso 3166 has been changed, but no manual shipping type
    // change has occured set it automatically
    if (
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_COUNTRY_ISO3166) &&
      !$this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_TYPE)
    ) {
      $this->updateShippingTypeFromCountryCode();
    }

    // if the shipping country iso 3166 and region has been changed
    // update tax amount
    if (
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_COUNTRY_ISO3166) ||
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_STATE_REGION)
    ) {
      $this->updateTaxAmount();
    }

    return parent::preSave($con);
  }

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
    return bcadd(
      bcadd($this->getPriceAmount('float'), $this->getTaxAmount('float'), 2),
      $this->getShippingFeeAmount('float'), 2
    );
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

  public function getPriceAmount($return = 'float')
  {
    $amount = parent::getPriceAmount();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function setRawPriceAmount($v)
  {
    return parent::setPriceAmount($v);
  }

  public function getRawPriceAmount()
  {
    return parent::getPriceAmount();
  }

  /**
   * @param integer|float|double $v
   *
   */
  public function setTaxAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 3), 100);
    }

    return parent::setTaxAmount($v);
  }

  public function getTaxAmount($return = 'float')
  {
    $amount = parent::getTaxAmount();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 3);
  }

  public function setRawTaxAmount($v)
  {
    return parent::setTaxAmount($v);
  }

  public function getRawTaxAmount()
  {
    return parent::getTaxAmount();
  }

  /**
   * @param integer|float|double $v
   * @return    ShoppingCartCollectible
   */
  public function setShippingFeeAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 2), 100);
    }

    parent::setShippingFeeAmount($v);
  }

  /**
   * Return the shipping fee amount, either as an integer or as an float.
   * If however the shipping fee amount is set to null it will be returned as is
   *
   * @param     string $return
   * @return    mixed
   */
  public function getShippingFeeAmount($return = 'float')
  {
    $amount = parent::getShippingFeeAmount();

    if (null === $amount)
    {
      return null;
    }

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function setRawShippingFeeAmount($v)
  {
    return parent::setShippingFeeAmount($v);
  }

  public function getRawShippingFeeAmount()
  {
    return parent::getShippingFeeAmount();
  }


  /**
   * Automatically update the shipping fee amount based either on a new country code
   * or on the currently set shipping country iso 3166
   *
   * If a country code is supplied, the current shipping country iso 3166 code
   * will be overwritten
   *
   * @param     string|null $country_code
   * @return    ShoppingCartCollectible
   */
  public function updateShippingFeeAmountFromCountryCode($country_code = null)
  {
    if (!empty($country_code))
    {
      $this->setShippingCountryIso3166($country_code);
    }

    $shipping_amount = $this->getCollectibleForSale()
      ->getShippingAmountForCountry($this->getShippingCountryIso3166(), 'integer');

    // if no shipping amout can be returned for a country we get "FALSE"
    if (false !== $shipping_amount)
    {
      // if we got a normal result simply update the shipping amount
      $this->setShippingFeeAmount($shipping_amount);
    }
    else
    {
      // in this case we set the shipping fee amount field to NULL to show that
      // it does not hold an actual value. Notice that null !== 0, which denounces
      // free shipping
      $this->setRawShippingFeeAmount(null);
    }

    return $this;
  }

  public function updateTaxAmount()
  {
    $this->setTaxAmount(0);
    /* @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->getCollectibleForSale();

    if ($collectible_for_sale->getTaxCountry() == $this->getShippingCountryIso3166() &&
      (!$collectible_for_sale->getTaxState()
        || $collectible_for_sale->getTaxState() == $this->getShippingStateRegion())
    )
    {
      $this->setTaxAmount(round(($this->getPriceAmount() / 100) * $collectible_for_sale->getTaxPercentage(), 2));
    }
  }

  /**
   * Automatically update the shipping type based either on a new country code
   * or on the currrently set shipping country iso 3166
   *
   * If a country code param is supplied it will overwrite the current one
   *
   * @param     string|null $country_code
   * @return    boolean
   */
  public function updateShippingTypeFromCountryCode($country_code = null)
  {
    if (!empty($country_code))
    {
      $this->setShippingCountryIso3166($country_code);
    }

    if (null !== $this->getShippingReference($this->getShippingCountryIso3166()))
    {
      $this->setShippingType($this->getShippingReference()->getShippingType());

      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * Will try to update both the shipping amount and the shipping type based
   * on a new shipping country code
   *
   * @param     string $country_code
   * @return    boolean
   */
  public function updateShippingFromCountryCode($country_code)
  {
    $this->updateShippingFeeAmountFromCountryCode($country_code);

    return $this->updateShippingTypeFromCountryCode($country_code);
  }

  /**
   * Get the shipping reference based on the currently set country iso 3166 or
   * manual parameter value
   *
   * @param     string|null $country_code
   * @param     PropelPDO $con
   *
   * @return    ShippingReference
   */
  public function getShippingReference($country_code = null, PropelPDO $con = null)
  {
    if (null === $this->aShippingReference || null !== $country_code)
    {
      $this->aShippingReference = $this->getCollectible($con)
        ->getShippingReferenceForCountryCode(
          $country_code ?: $this->getShippingCountryIso3166(),
          $con);
    }

    return $this->aShippingReference;
  }

  /**
   * @return    ShoppingCartCollectible
   */
  public function clearShippingReference()
  {
    $this->aShippingReference = null;

    return $this;
  }

  public function getShippingCountryName(PropelPDO $con = null)
  {
    $q = iceModelGeoCountryQuery::create()
       ->filterByIso3166($this->getShippingCountryIso3166());

    if (( $country = $q->findOne($con) ))
    {
      return $country->getName();
    }

    return null;
  }

}
