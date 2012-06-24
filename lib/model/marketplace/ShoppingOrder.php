<?php

require 'lib/model/marketplace/om/BaseShoppingOrder.php';

class ShoppingOrder extends BaseShoppingOrder
{

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

  public function getSeller()
  {
    return CollectorQuery::create()
      ->findOneById($this->getSellerId());
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

  public function getCurrency()
  {
    return $this->getShoppingCartCollectible()->getPriceCurrency();
  }

  /**
   * price + tax + shipping
   *
   * @return type
   */
  public function getTotalAmount()
  {
    return bcadd(
      bcadd($this->getCollectiblesAmount(), $this->getTaxAmount(), 2),
      $this->getShippingFeeAmount(), 2
    );
  }

  public function getTaxAmount()
  {
    return 0;
  }

  public function getCollectiblesAmount()
  {
    return $this->getShoppingCartCollectible()->getPriceAmount();
  }

  public function getShippingFeeAmount($return = 'float')
  {
    return $this->getShoppingCartCollectible()
      ->getShippingFeeAmount($return);
  }

  public function getDescription()
  {
    return $this->getShoppingCartCollectible()->getDescription();
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
      'Memo' => $this->getNoteToSeller(),

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
}
