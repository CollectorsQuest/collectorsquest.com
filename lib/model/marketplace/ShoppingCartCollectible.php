<?php

require 'lib/model/marketplace/om/BaseShoppingCartCollectible.php';

class ShoppingCartCollectible extends BaseShoppingCartCollectible
{
  /* @var ShippingReference */
  protected $aShippingReference;

  /* @var SellerPromotion */
  protected $aSellerPromotion;

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
    )
    {
      $this->updateShippingFeeAmountFromCountryCode();
    }

    // if the shipping country iso 3166 has been changed, but no manual shipping type
    // change has occured set it automatically
    if (
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_COUNTRY_ISO3166) &&
      !$this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_TYPE)
    )
    {
      $this->updateShippingTypeFromCountryCode();
    }

    // if the shipping country iso 3166 and region has been changed
    // update tax amount
    if (
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_COUNTRY_ISO3166) ||
      $this->isColumnModified(ShoppingCartCollectiblePeer::SHIPPING_STATE_REGION)
    )
    {
      $this->updateTaxAmount();
    }

    return parent::preSave($con);
  }

  public function postDelete(PropelPDO $con = null)
  {
    // Remove all not finished orders for this collectible
    ShoppingOrderQuery::create()
      ->filterByCollectibleId($this->getCollectibleId())
      ->filterByShoppingCartId($this->getShoppingCartId())
      ->filterByShoppingPaymentId(null, Criteria::ISNULL)
      ->delete();

    return parent::postDelete($con);
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
      bcadd(bcsub($this->getPriceAmount('float'), $this->getPromotionAmount('float'), 2), $this->getTaxAmount('float'), 2),
      $this->getShippingFeeAmount('float'), 2
    );
  }

  /**
   * @param integer|float|double $v
   * @return ShoppingCartCollectible|void
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
   * @return ShoppingCartCollectible
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
   * @param     bool $update_discount
   * @return    ShoppingCartCollectible
   */
  public function updateShippingFeeAmountFromCountryCode($country_code = null, $update_discount = true)
  {
    if (!empty($country_code))
    {
      $this->setShippingCountryIso3166($country_code);
    }

    $shipping_amount = $this->getCollectibleForSale()
      ->getShippingAmountForCountry($this->getShippingCountryIso3166(), 'integer');

    // if no shipping amout can be returned for a country we get "FALSE"
    if (false === $shipping_amount)
    {
      // in this case we set the shipping fee amount field to NULL to show that
      // it does not hold an actual value. Notice that null !== 0, which denounces
      // free shipping
      $this->setRawShippingFeeAmount(null);
    }
    else
    {
      // if we got a normal result simply update the shipping amount
      $this->setShippingFeeAmount($shipping_amount);
    }

    if ($update_discount)
    {
      $this->updateSellerPromotionAmount();
    }

    return $this;
  }

  public function updateTaxAmount()
  {
    $this->setTaxAmount(0);
    /* @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->getCollectibleForSale();

    if (
      $collectible_for_sale->getTaxCountry() == $this->getShippingCountryIso3166() &&
      (!$collectible_for_sale->getTaxState() || $collectible_for_sale->getTaxState() == $this->getShippingStateRegion())
    )
    {
      $this->setTaxAmount(round((bcsub($this->getPriceAmount(), $this->getPromotionAmount(), 2) / 100)
        * $collectible_for_sale->getTaxPercentage(), 2));
    }

    return $this;
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
      $aShippingReference = $this->getCollectible($con)
        ->getShippingReferenceForCountryCode(
          $country_code ?: $this->getShippingCountryIso3166(),
          $con
        );

      // if we are getting the shipping reference for the default country code
      if (null === $country_code || $this->getShippingCountryIso3166() == $country_code)
      {
        // then save a reference in this object
        $this->aShippingReference = $aShippingReference;
      }
      else
      {
        // otherwize return the object without saving a reference, so that
        // when the method is called with the default country code we don't
        // get the wrong shipping reference
        return $aShippingReference;
      }
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

  public function setPromotionAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 3), 100);
    }

    return parent::setPromotionAmount($v);
  }

  public function getPromotionAmount($return = 'float')
  {
    $amount = parent::getPromotionAmount();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 3);
  }

  /**
   * Get SellerPromotion
   *
   * @return SellerPromotion|null
   */
  public function getSellerPromotion()
  {
    if ($this->aSellerPromotion === null && $this->getSellerPromotionId() != null)
    {
      return $this->aSellerPromotion = SellerPromotionQuery::create()->findOneById($this->getSellerPromotionId());
    }
    else
    {
      return $this->aSellerPromotion;
    }
  }

  /**
   * Set SellerPromotion
   *
   * @param SellerPromotion $seller_promotion
   * @return ShoppingCartCollectible
   */
  public function setSellerPromotion($seller_promotion = null)
  {
    if ($seller_promotion instanceof SellerPromotion)
    {
      $this->setSellerPromotionId($seller_promotion->getId());
      $this->aSellerPromotion = $seller_promotion;
    }
    else
    {
      $this->setSellerPromotionId(null);
      $this->setPromotionAmount(0);
      $this->aSellerPromotion = null;
    }
    $this->updateSellerPromotionAmount();

    return $this;
  }

  /**
   * Update SellerPromotion Amount
   *
   * @return ShoppingCartCollectible
   */
  public function updateSellerPromotionAmount()
  {
    /* @var $seller_promotion SellerPromotion */
    $seller_promotion = $this->getSellerPromotion();
    if ($seller_promotion instanceof SellerPromotion)
    {
      switch ($seller_promotion->getAmountType())
      {
        case SellerPromotionPeer::AMOUNT_TYPE_FREE_SHIPPING:
          $this->setPromotionAmount(0);
          $this->setRawShippingFeeAmount(0);
          break;
        case SellerPromotionPeer::AMOUNT_TYPE_FIXED:
          $this->setPromotionAmount($seller_promotion->getAmount());
          if ($this->getRawShippingFeeAmount() == 0)
          {
            // Need restore shipping if we change code for free shipping
            $this->updateShippingFeeAmountFromCountryCode(null, false);
            $this->updateShippingTypeFromCountryCode();
          }
          break;
        case SellerPromotionPeer::AMOUNT_TYPE_PERCENTAGE:
          $this->setPromotionAmount(
            round(($this->getPriceAmount() / 100) * $seller_promotion->getAmount(), 2 )
          );
          if ($this->getRawShippingFeeAmount() == 0)
          {
            // Need restore shipping if we change code for free shipping
            $this->updateShippingFeeAmountFromCountryCode(null, false);
            $this->updateShippingTypeFromCountryCode();
          }
          break;
      }
    }
    else
    {
      $this->setPromotionAmount(0);
      if ($this->getRawShippingFeeAmount() == 0)
      {
        // Need restore shiping if we remove code for free shipping
        $this->updateShippingFeeAmountFromCountryCode(null, false);
        $this->updateShippingTypeFromCountryCode();
      }
    }
    $this->updateTaxAmount();

    return $this;
  }
}
