<?php

require 'lib/model/marketplace/om/BaseShoppingPayment.php';

class ShoppingPayment extends BaseShoppingPayment
{
  /* @var SellerPromotion */
  protected $aSellerPromotion;

  /**
   * @return null|string
   */
  public function getTrackingId()
  {
    if (!$this->isNew())
    {
      return 'SP-'. $this->getId() .'-'. date('is');
    }

    return null;
  }

  public function getTransactionId()
  {
    switch ($this->getProcessor())
    {
      case ShoppingPaymentPeer::PROCESSOR_PAYPAL:
        return $this->getProperty('paypal.transaction_id');
        break;
    }

    return null;
  }

  public function getSenderEmail()
  {
    switch ($this->getProcessor())
    {
      case ShoppingPaymentPeer::PROCESSOR_PAYPAL:
        return $this->getProperty('paypal.sender_email');
        break;
    }

    return null;
  }

  public function setShoppingOrder(ShoppingOrder $shopping_order)
  {
    /**
     * Set the shopping_order_id
     */
    $this->setShoppingOrderId($shopping_order->getId());

    /**
     * Set all the money amounts
     */
    $this->setAmountTotal($shopping_order->getTotalAmount('integer'));
    $this->setAmountShippingFee($shopping_order->getShippingFeeAmount('integer'));
    $this->setAmountCollectibles($shopping_order->getCollectiblesAmount('integer'));
    $this->setAmountTax($shopping_order->getTaxAmount('integer'));
    $this->setAmountPromotion($shopping_order->getPromotionAmount());
    $this->setSellerPromotionId($shopping_order->getSellerPromotionId());
  }

  public function getAmountCollectibles($return = 'float')
  {
    $amount = parent::getAmountCollectibles();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function getAmountShippingFee($return = 'float')
  {
    $amount = parent::getAmountShippingFee();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function getAmountTax($return = 'float')
  {
    $amount = parent::getAmountTax();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  public function getAmountTotal($return = 'float')
  {
    $amount = parent::getAmountTotal();

    return ($return === 'integer') ? $amount : bcdiv($amount, 100, 2);
  }

  /**
   * @param  array  $request
   * @return void
   */
  public function setPayPalPayRequest($request)
  {
    foreach ((array) $request as $key => $values)
    {
      $key = sfInflector::underscore($key);
      $this->setProperty('paypal.'. $key, serialize($values));
    }
  }

  /**
   * @return array
   */
  public function getPayPalErrors()
  {
    $result = array();
    if ($p = $this->getProperty(ShoppingPaymentPeer::PAYPAL_ERROR))
    {
      $p = unserialize($p);
      if (isset($p['Errors']))
      {
        $result = $p['Errors'];
      }
    }
    return $result;
  }

  public function setAmountPromotion($v)
  {
    if (!is_integer($v) && !ctype_digit($v))
    {
      $v = bcmul(cqStatic::floatval($v, 3), 100);
    }

    return parent::setAmountPromotion($v);
  }

  public function getAmountPromotion($return = 'float')
  {
    $amount = parent::getAmountPromotion();

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
}
