<?php

require 'lib/model/marketplace/om/BaseShoppingOrder.php';

class ShoppingOrder extends BaseShoppingOrder
{


  /**
   * Pre save hook
   *
   * @param     PropelPDO $con
   * @return    boolean
   */
  public function preSave(PropelPDO $con = null)
  {
    // if the shipping country iso 3166 and region has been changed
    // update tax amount
    if (
      $this->isColumnModified(ShoppingOrderPeer::SHIPPING_STATE_REGION) ||
      $this->isColumnModified(ShoppingOrderPeer::SHIPPING_COUNTRY_ISO3166)
    ) {
      $this->updateTaxAmount();
    }

    return parent::preSave($con);
  }

  /**
   * @param  null|PropelPDO  $con
   */
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

  public function preDelete(PropelPDO $con = null)
  {
    /* @var $shopping_payment ShoppingPayment */
    if ($shopping_payment = $this->getShoppingPayment())
    {
      // Archive and delete related ShoppingPayment objects
      $shopping_payment->delete($con);
    }

    return parent::preDelete($con);
  }

  public function getBuyer()
  {
    return CollectorQuery::create()
      ->findOneById($this->getCollectorId());
  }

  /**
   * @param  null|PropelPDO  $con
   * @return CollectibleForSale
   */
  public function getCollectibleForSale(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollectibleForSale($con);
  }

  /**
   * @param  null|PropelPDO  $con
   * @return ShoppingCartCollectible
   */
  public function getShoppingCartCollectible(PropelPDO $con = null)
  {
    $q = ShoppingCartCollectibleQuery::create()
       ->filterByCollectibleId($this->getCollectibleId())
       ->filterByShoppingCartId($this->getShoppingCartId());

    return $q->findOne($con);
  }

  public function getDescription()
  {
    return $this->getShoppingCartCollectible()->getDescription();
  }

  /**
   * price + tax + shipping
   *
   * @param  string  $return
   * @param float for case if need recalculate total with different tax percentages
   * @return float
   */
  public function getTotalAmount($return = 'float', $percentage = null)
  {
    if ($return === 'integer')
    {
      return array_sum(array(
        $this->getCollectiblesAmount('integer'),
        $this->getTaxAmount('integer', $percentage),
        $this->getShippingFeeAmount('integer')
      ));
    }
    else
    {
      return bcadd(
        bcadd($this->getCollectiblesAmount(), $this->getTaxAmount($return, $percentage), 2),
        $this->getShippingFeeAmount(), 2
      );
    }
  }

  public function getTaxAmount($return = 'float', $percentage = null)
  {
    if ($percentage !== null)
    {
      return round(($this->getCollectiblesAmount($return) / 100) * $percentage, 2);
    }
    if ($payment = $this->getShoppingPaymentRelatedByShoppingPaymentId())
    {
      return $payment->getAmountTax($return);
    }
    else if ($collectible = $this->getShoppingCartCollectible())
    {
      return $collectible->getTaxAmount($return);
    }

    return null;
  }

  public function getCollectiblesAmount($return = 'float')
  {
    if ($payment = $this->getShoppingPaymentRelatedByShoppingPaymentId())
    {
      return $payment->getAmountCollectibles($return);
    }
    else if (count($this->getShoppingOrderCollectibles()))
    {
      $result = null;
      foreach ($this->getShoppingOrderCollectibles() as $shopping_order_collectible)
      {
        if ($return == 'float')
        {
          $result =   bcadd($result, $shopping_order_collectible->getPriceAmount($return), 2);
        }
        else
        {
          $result = $result + $shopping_order_collectible->getPriceAmount($return);
        }
      }
      return $result;
    }

    return null;
  }

  /**
   * Get the combined shipping fee amount for the order.
   *
   * Will return NULL if shipping type for the selected country is NO_SHIPPING
   *
   * @param     string $return
   * @return    mixed
   */
  public function getShippingFeeAmount($return = 'float')
  {
    if (
      ($shipping_reference = $this->getShippingReference()) &&
      ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $shipping_reference->getShippingType()
    )
    {
      return null;
    }

    if ($payment = $this->getShoppingPaymentRelatedByShoppingPaymentId())
    {
      return $payment->getAmountShippingFee($return);
    }
    else if (count($this->getShoppingOrderCollectibles()))
    {
      return $this->getShippingFeeTotalAmount($return);
    }

    return null;
  }

  public function setShippingAddress(CollectorAddress $address)
  {
    $this->setShippingFullName($address->getFullName());
    $this->setShippingAddressLine1($address->getAddressLine1());
    $this->setShippingAddressLine2($address->getAddressLine2());
    $this->setShippingCity($address->getCity());
    $this->setShippingStateRegion($address->getStateRegion());
    $this->setShippingZipPostcode($address->getZipPostcode());
    $this->setShippingCountryIso3166($address->getCountryIso3166());

    // update shopping cart collectible shipping based on new
    // shipping address country
    foreach ($this->getShoppingOrderCollectibles() as $shopping_order_collectible)
    {
      /** @var $shopping_cart_collectible ShoppingCartCollectible */
      $shopping_cart_collectible = ShoppingCartCollectiblePeer::retrieveByPK(
        $this->getShoppingCartId(), $shopping_order_collectible->getCollectibleId()
      );
      $shopping_cart_collectible
        ->updateShippingFromCountryCode($address->getCountryIso3166())
        ->save();
    }

    return $this;
  }

  public function getShippingCountryName()
  {
    $country = iceModelGeoCountryQuery::create()
      ->findOneByIso3166($this->getShippingCountryIso3166());

    return $country
      ? $country->getName()
      : '';
  }

  public function getShippingStateRegionName()
  {
    $region = iceModelGeoRegionQuery::create()
      ->findOneById($this->getShippingStateRegion());

    return $region
      ? $region->getName()
      : '';
  }

  public function getPaypalPayRequestFields()
  {
    $PayRequestFields = array(
      // Required.  Whether the request pays the receiver or whether the request is set up to
      // create a payment request, but not fulfill the payment until the ExecutePayment is called.
      // Values are:  PAY, CREATE, PAY_PRIMARY
      'ActionType' => 'CREATE',
      'CurrencyCode' => $this->getPriceCurrency(),

      // The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
      'FeesPayer' => 'EACHRECEIVER',

      // A note associated with the payment (text, not HTML).  1000 char max
      'Memo' => trim(
        $this->getNoteToSeller() .
        ' (This transaction was initiated on collectorsquest.com!'.
        ' Please, go to https://www.collectorsquest.com/mycq/marketplace for more information)'
      ),

      // Whether to reverse paralel payments if an error occurs with a payment.
      // Values are:  TRUE, FALSE
      'ReverseAllParallelPaymentsOnError' => true,

      // Sender's email address.  127 char max.
      'SenderEmail' => '',

      // Unique ID that you specify to track the payment.  127 char max.
      'TrackingID' => ''
    );

    return $PayRequestFields;
  }

  public function getPaypalClientDetailsFields()
  {
    $ClientDetailsFields = array(
      // Your ID for the sender  127 char max.
      'CustomerID' => $this->getCollectorId(),
      // Your ID of the type of customer.  127 char max.
      'CustomerType' => 'Collector',
      // Sender's geographic location
      'GeoLocation' => '',
      // A sub-identification of the application.  127 char max.
      'Model' => 'Order',
      // Your organization's name or ID
      'PartnerName' => 'Collectors Quest, Inc.'
    );

    return $ClientDetailsFields;
  }

  public function getPaypalReceivers()
  {
    $Receivers = array();
    $Receiver = array(
      // Required.  Amount to be paid to the receiver.
      'Amount' => $this->getTotalAmount(),

      // Receiver's email address. 127 char max.
      'Email' => $this->getSeller()->getSellerSettingsPaypalEmail() ?
        $this->getSeller()->getSellerSettingsPaypalEmail() :
        $this->getSeller()->getEmail(),

      // The invoice number for the payment.  127 char max.
      'InvoiceID' => $this->getUuid() .'-'. date('is'),

      // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
      'PaymentType' => 'GOODS',

      // The transaction subtype for the payment.
      'PaymentSubType' => '',

      // Receiver's phone number.   Numbers only.
      'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''),

      // Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
      'Primary' => false
    );
    array_push($Receivers, $Receiver);

    return $Receivers;
  }

  public function getPaypalSenderIdentifierFields()
  {
    $SenderIdentifierFields = array(
      // If TRUE, use credentials to identify the sender.  Default is false.
      'UseCredentials' => false
    );

    return $SenderIdentifierFields;
  }

  public function getPaypalAccountIdentifierFields()
  {
    $AccountIdentifierFields = array(
      // Sender's email address.  127 char max.
      'Email' => '',

      // Sender's phone number.  Numbers only.
      'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')
    );

    return $AccountIdentifierFields;
  }

  public function getPaypalFundingTypes()
  {
    return array('ECHECK', 'BALANCE', 'CREDITCARD');
  }



  public function getShoppingPayment()
  {
    return $this->getShoppingPaymentRelatedByShoppingPaymentId();
  }



  public function getHash($version = 'v1', $time = null, $salt = null)
  {
    $time = is_numeric($time) ? $time : time();
    $salt = !empty($salt) ? (string) $salt : $this->getUuid();

    switch ($version)
    {
      case 'v1':
      default:
        // Making sure the version is good value
        $version = 'v1';

        $json = json_encode(array(
          'version' => $version,
          'id'      => $this->getId(),
          'created' => (int) $this->getCreatedAt('U'),
          'time'    => (int) $time
        ));

        $hash = sprintf(
          '%s;%d;%s;%d',
          $version, $this->getId(),
          hash_hmac('sha1', base64_encode($json), $salt), $time
        );
        break;
    }

    return $hash;
  }

  /**
   * Getting first ShoppingOrderCollectible
   *
   * @return ShoppingOrderCollectible
   */
  public function getFirstShoppingOrderCollectible()
  {
    $shopping_order_collectibles = $this->getShoppingOrderCollectibles();
    return reset($shopping_order_collectibles);
  }

  /**
   * Get Collector Seller
   *
   * @return Collector
   */
  public function getSeller()
  {
    return $this->getFirstShoppingOrderCollectible()->getCollector();
  }

  /**
   * Get Currency
   *
   * @return string
   */
  public function getPriceCurrency()
  {
    return $this->getFirstShoppingOrderCollectible()->getPriceCurrency();
  }

  /**
   * Get group key
   *
   * @return string
   */
  public function getGroupKey()
  {
    $key = parent::getGroupKey();
    if ($key === null && $this->getFirstShoppingOrderCollectible())
    {
      $key = $this->getFirstShoppingOrderCollectible()->getGroupKey();
      $this->setGroupKey($key);
    }
    return $key;
  }

  /**
   * sum of collectibles price
   *
   * @param string $return
   * @return int|float
   */
  public function getTotalPrice($return = 'float')
  {
    $result = null;
    /** @var $shopping_order_collectible ShoppingOrderCollectible */
    foreach ($this->getShoppingOrderCollectibles() as $shopping_order_collectible)
    {
      if ($return == 'float')
      {
        $result =   bcadd($result, $shopping_order_collectible->getTotalPrice($return), 2);
      }
      else
      {
        $result = $result + $shopping_order_collectible->getTotalPrice($return);
      }
    }
    return $result;
  }

  /**
   * sum of collectibles ShippingFee
   *
   * @param string $return
   * @return int|float
   */
  public function getShippingFeeTotalAmount($return = 'float')
  {
    $result = null;
    /** @var $shopping_order_collectible ShoppingOrderCollectible */
    foreach ($this->getShoppingOrderCollectibles() as $shopping_order_collectible)
    {
      if ($return == 'float')
      {
        $result =   bcadd($result, $shopping_order_collectible->getShippingFeeAmount($return), 2);
      }
      else
      {
        $result = $result + $shopping_order_collectible->getShippingFeeAmount($return);
      }
    }
    return $result;
  }

  /**
   * Check is order cannot be shipped
   *
   * @return bool
   */
  public function isCannotShip()
  {
    $shopping_order_collectibles = $this->getShoppingOrderCollectibles();
    foreach ($shopping_order_collectibles as $shopping_order_collectible)
    {
      if ($shopping_order_collectible->isCannotShip())
      {
          return true;
      }
    }
    return false;
  }

  /**
   * Clears out and remove from DB the collShoppingOrderCollectibles collection
   *
   * @return ShoppingOrder
   */
  public function deleteShoppingOrderCollectibles()
  {
    $c = new Criteria();
    $c->add(ShoppingOrderCollectiblePeer::SHOPPING_ORDER_ID, $this->getId());
    ShoppingOrderCollectiblePeer::doDelete($c);
    $this->clearShoppingOrderCollectibles();
    return $this;
  }

  public function getSellerId()
  {
    return $this->getFirstShoppingOrderCollectible()->getSellerId();
  }

  public function updateTaxAmount()
  {
    /* @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->getCollectibleForSale();

    $haveTax = false;
    if ($collectible_for_sale->getTaxCountry() == $this->getShippingCountryIso3166() &&
      (!$collectible_for_sale->getTaxState()
        || $collectible_for_sale->getTaxState() == (integer) $this->getShippingStateRegion())
    )
    {
      $haveTax = true;
    }

    if ($payment = $this->getShoppingPaymentRelatedByShoppingPaymentId())
    {
      $tax = $haveTax ? (round(($payment->getAmountCollectibles() / 100) * $collectible_for_sale->getTaxPercentage(), 2)) : 0;
      if (!is_integer($tax) && !ctype_digit($tax))
      {
        $tax = bcmul(cqStatic::floatval($tax, 2), 100);
      }
      $payment
        ->setAmountTax($tax)
        ->save();
    }
    else if ($cart = $this->getShoppingCartCollectible())
    {
      $tax = $haveTax ? (round(($cart->getPriceAmount() / 100) * $collectible_for_sale->getTaxPercentage(), 2)) : 0;
      $cart
        ->setTaxAmount($tax)
        ->save();
    }
  }

}
