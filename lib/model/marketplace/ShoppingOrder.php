<?php

require 'lib/model/marketplace/om/BaseShoppingOrder.php';

class ShoppingOrder extends BaseShoppingOrder
{

  /** @var ShippingReference */
  protected $aShippingReference;

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
    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $this->getShoppingPayment();

    // Archive and delete related ShoppingPayment objects
    $shopping_payment->delete($con);

    return parent::preDelete($con);
  }

  public function getSeller()
  {
    return CollectorQuery::create()
      ->findOneById($this->getSellerId());
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

  public function getCurrency()
  {
    $currency = 'USD';

    if ($collectible = $this->getShoppingCartCollectible())
    {
      $currency = $collectible->getPriceCurrency();
    }

    return $currency;
  }

  /**
   * price + tax + shipping
   *
   * @param  string  $return
   * @return float
   */
  public function getTotalAmount($return = 'float')
  {
    if ($return === 'integer')
    {
      return array_sum(array(
        $this->getCollectiblesAmount('integer'),
        $this->getTaxAmount('integer'),
        $this->getShippingFeeAmount('integer')
      ));
    }
    else
    {
      return bcadd(
        bcadd($this->getCollectiblesAmount(), $this->getTaxAmount(), 2),
        $this->getShippingFeeAmount(), 2
      );
    }
  }

  public function getTaxAmount($return = 'float')
  {
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
    else if ($collectible = $this->getShoppingCartCollectible())
    {
      return $collectible->getPriceAmount($return);
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
    else if ($collectible = $this->getShoppingCartCollectible())
    {
      return $collectible->getShippingFeeAmount($return);
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
    $this->getShoppingCartCollectible()
      ->setShippingCountryIso3166($address->getCountryIso3166())
      ->updateShippingFeeAmountFromCountryCode()
      ->save();
  }

  public function getShippingCountryName()
  {
    $geo_country = GeoCountryQuery::create()
      ->findOneByIso3166($this->getShippingCountryIso3166());

    return $geo_country ? $geo_country->getName() : '';
  }

  public function getPaypalPayRequestFields()
  {
    $PayRequestFields = array(
      // Required.  Whether the request pays the receiver or whether the request is set up to
      // create a payment request, but not fulfill the payment until the ExecutePayment is called.
      // Values are:  PAY, CREATE, PAY_PRIMARY
      'ActionType' => 'CREATE',
      'CurrencyCode' => $this->getCurrency(),

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

  public function getShoppingPayment()
  {
    return $this->getShoppingPaymentRelatedByShoppingPaymentId();
  }

  /**
   * @return    ShoppingCartCollectible
   */
  public function clearShippingReference()
  {
    $this->aShippingReference = null;

    return $this;
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

}
