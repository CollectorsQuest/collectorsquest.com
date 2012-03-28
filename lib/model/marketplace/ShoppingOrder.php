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
}
