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

  /**
   * @param  null|PropelPDO  $con
   * @return CollectibleForSale
   */
  public function getCollectibleForSale(PropelPDO $con = null)
  {
    $this->getCollectible($con)->getCollectibleForSale($con);
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

  public function getTotalAmount()
  {
    return $this->getShoppingCartCollectible()->getPriceAmount();
  }

  public function getCurrency()
  {
    return $this->getShoppingCartCollectible()->getPriceCurrency();
  }

  public function getShippingFeeAmount()
  {
    return $this->getShoppingCartCollectible()->getShippingFeeAmount();
  }

  public function getDescription()
  {
    return $this->getShoppingCartCollectible()->getDescription();
  }

  public function getPaypalSECFields()
  {
    return array(
      'maxamt' => $this->getTotalAmount() + $this->getShippingFeeAmount(),
      'reqconfirmshipping' => '1',
      'noshipping' => '0',
      'allownote' => '1',
      'addroverride' => '1',
      'localecode' => 'en',
      'skipdetails' => '1',
      'email' => '', // Email address of the buyer as entered during checkout.  PayPal uses this value to pre-fill the PayPal sign-in page.  127 char max.
      'solutiontype' => 'Mark',
      'landingpage' => 'Billing',
      'channeltype' => 'Merchant',
      'brandname' => 'CollectorsQuest.com',
      'customerservicenumber' => '646-558-6360',
      'giftmessageenable' => '0',
      'giftreceiptenable' => '0',
      'giftwrapenable' => '0',
      'buyeremailoptionenable' => '0',
      'surveyenable' => '0',
      'allowpushfunding' => '0'
    );
  }

  public function getPaypalDECFields($token, $payer_id)
  {
    return array(
      'token' => $token,
      'payerid' => $payer_id,
      'returnfmfdetails' => '0',
      'allowedpaymentmethod' => 'InstantPaymentOnly',
    );
  }

  public function getPaypalPayments()
  {
    return array(
      0 => array(
        'amt' => $this->getTotalAmount() + $this->getShippingFeeAmount(),
        'currencycode' => $this->getCurrency(),
        'itemamt' => $this->getTotalAmount(),
        'shippingamt' => $this->getShippingFeeAmount(),
        'desc' => $this->getDescription(),
        'custom' => '',
        'invnum' => $this->getUuid() .'-'. rand(1,999),
        'notetext' => $this->getNoteToSeller(),
        'allowedpaymentmethod' => 'InstantPaymentOnly',
        'paymentaction' => 'Sale',
        'order_items' => $this->getPaypalItems()
      )
    );
  }

  public function getPaypalItems()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getCollectible();

    $items = array(
      0 => array(
        'name'   => $collectible->getName(),
        'desc'   => $collectible->getDescription('stripped', 85),
        'amt'    => $this->getShoppingCartCollectible()->getPriceAmount('float'),
        'number' => $this->getUuid() .'-'. $collectible->getId(),
        'qty'    => '1',
        'taxamt' => $this->getShoppingCartCollectible()->getTaxAmount('float')
      )
    );

    return $items;
  }



  public function getPaypalPayRequestFields()
  {
    $PayRequestFields = array(
      // Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
      'ActionType' => 'CREATE',
      'CurrencyCode' => $this->getCurrency(),
      // The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
      'FeesPayer' => 'EACHRECEIVER',
      // A note associated with the payment (text, not HTML).  1000 char max
      'Memo' => $this->getNoteToSeller(),
      // Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
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
      'Model' => '',
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
      'Amount' => $this->getTotalAmount() + $this->getShippingFeeAmount(),
      // Receiver's email address. 127 char max.
      'Email' => 'kangov_1327417143_biz@collectorsquest.com',
      // The invoice number for the payment.  127 char max.
      'InvoiceID' => $this->getUuid() .'-'. rand(1,999),
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
      'UseCredentials' => ''
    );

    return $SenderIdentifierFields;
  }

  public function getPaypalAccountIdentifierFields()
  {
    $AccountIdentifierFields = array(
      // Sender's email address.  127 char max.
      'Email' => 'kangov_1327417552_per@collectorsquest.com',
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
