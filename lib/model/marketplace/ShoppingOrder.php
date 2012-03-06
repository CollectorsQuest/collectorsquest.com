<?php

require 'lib/model/marketplace/om/BaseShoppingOrder.php';

class ShoppingOrder extends BaseShoppingOrder
{
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

  public function getCollectible()
  {
    return $this->getCollectibleForSale()->getCollectible();
  }

  public function getTotalAmount()
  {
    return $this->getCollectibleForSale()->getPrice();
  }

  public function getCurrency()
  {
    return 'USD';
  }

  public function getShippingAmount()
  {
    return '0';
  }

  public function getDescription()
  {
    return '';
  }

  public function getPaypalSECFields()
  {
    return array(
      'maxamt' => $this->getTotalAmount() + $this->getShippingAmount(),
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
        'amt' => $this->getTotalAmount() + $this->getShippingAmount(),
        'currencycode' => $this->getCurrency(),
        'itemamt' => $this->getTotalAmount(),
        'shippingamt' => $this->getShippingAmount(),
        'desc' => $this->getDescription(),
        'custom' => '',
        'invnum' => $this->getUuid(),
        'notetext' => $this->getNoteToSeller(),
        'allowedpaymentmethod' => 'InstantPaymentOnly',
        'paymentaction' => 'Sale',
        'order_items' => $this->getPaypalItems()
      )
    );
  }

  public function getPaypalItems()
  {
    /** @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->getCollectibleForSale();

    /** @var $collectible Collectible */
    $collectible = $this->getCollectible();

    $items = array(
      0 => array(
        'name'   => $collectible->getName(),
        'desc'   => $collectible->getDescription('stripped', 127),
        'amt'    => $collectible_for_sale->getPrice(),
        'number' => $this->getUuid() .'-'. $collectible_for_sale->getId(),
        'qty'    => '1',
        'taxamt' => '0'
      )
    );

    return $items;
  }
}
