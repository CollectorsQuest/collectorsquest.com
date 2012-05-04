<?php

class mycqActions extends cqFrontendActions
{
  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeProfile()
  {
    return sfView::SUCCESS;
  }

  public function executeCollections()
  {
    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    return sfView::SUCCESS;
  }

  public function executeCollection()
  {
    $collection = $this->getRoute()->getObject();

    return sfView::SUCCESS;
  }

  public function executeCollectibles()
  {
    return sfView::SUCCESS;
  }

  public function executeCollectible()
  {
    return sfView::SUCCESS;
  }

  public function executeMarketplace()
  {
    return sfView::SUCCESS;
  }

  public function executeWanted()
  {
    return sfView::SUCCESS;
  }

  public function executeShoppingOrders()
  {
    return sfView::SUCCESS;
  }

  public function executeShoppingOrder()
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    // Prepare request arrays
    $GetShippingAddressFields = array(
      'Key' => $shopping_payment->getProperty('paypal.pay_key')
    );
    $PayPalRequestData = array('GetShippingAddressFields' => $GetShippingAddressFields);

    $AdaptivePayments = cqStatic::getPayPaylAdaptivePaymentsClient();
    $result = $AdaptivePayments->GetShippingAddress($PayPalRequestData);

    dd($result);

    return sfView::SUCCESS;
  }
}
