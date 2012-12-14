<?php

require 'lib/model/marketplace/om/BaseShoppingOrderCollectible.php';

class ShoppingOrderCollectible extends BaseShoppingOrderCollectible
{
  /** @var ShippingReference */
  protected $aShippingReference;

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

  public function getCondition(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)->getCondition();
  }

  public function getTotalPrice($return = 'float')
  {
    if ($return == 'integer')
    {
      return $this->getPriceAmount($return) + $this->getTaxAmount($return) + $this->getShippingFeeAmount($return);
    }
    return bcadd(
      bcadd($this->getPriceAmount($return), $this->getTaxAmount($return), 2),
      $this->getShippingFeeAmount($return), 2
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
   */
  public function setTaxAmount($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 2), 100);
    }

    parent::setTaxAmount($v);
  }

  public function getTaxAmount($return = 'float')
  {
    $amount = parent::getTaxAmount();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function setRawTaxAmount($v)
  {
    return parent::setTaxAmount($v);
  }

  public function getRawTaxAmount()
  {
    return parent::getTaxAmount();
  }

  public function getTotalAmount($return = 'float')
  {
    if ($return === 'integer')
    {
      return array_sum(array(
        $this->getPriceAmount('integer'),
        $this->getTaxAmount('integer'),
        $this->getShippingFeeAmount('integer')
      ));
    }
    else
    {
      return bcadd(
        bcadd($this->getPriceAmount(), $this->getTaxAmount(), 2),
        $this->getShippingFeeAmount(), 2
      );
    }
  }
  /**
   * @param integer|float|double $v
   */
  public function setShippingFeeAmount($v)
  {
    if ($v !== null)
    {
      if (!is_integer($v) && !ctype_digit($v))
      {
        $v = bcmul(cqStatic::floatval($v, 2), 100);
      }
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

  public function getShippingCountryName(PropelPDO $con = null)
  {
    $q = GeoCountryQuery::create()
      ->filterByIso3166($this->getShoppingOrder()->getShippingCountryIso3166());

    if ($geo_country = $q->findOne($con))
    {
      return $geo_country->getName();
    }

    return null;
  }

  /**
   * Building array key for grouping orders by predefined columns
   *
   * @return string
   */
  public function getGroupKey()
  {
    $fields = array();
    foreach (ShoppingOrderCollectiblePeer::$group_fields as $field)
    {
      $method = 'get'.$field;
      $fields[] = $this->$method();
    }
    return implode('_', $fields);
  }

  /**
   * Check is collectible cannot be shipped
   *
   * @return bool
   */
  public function isCannotShip()
  {
    return
      ShoppingCartCollectiblePeer::SHIPPING_TYPE_NO_SHIPPING ==
        $this->getShippingType() &&
        $this->getShippingFeeAmount() === null;

  }

  /**
   * Proxy method to get CollectibleForSale
   *
   * @return CollectibleForSale
   */
  public function getCollectibleForSale()
  {
    return $this->getCollectible()->getCollectibleForSale();
  }

  public function getCollectorId()
  {
    return $this->getShoppingOrder()->getCollectorId();
  }

}
