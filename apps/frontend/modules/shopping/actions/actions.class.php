<?php

class shoppingActions extends cqFrontendActions
{
  public function preExecute()
  {
    $this->forward404If(IceGateKeeper::locked('shopping_cart'));
    SmartMenu::setSelected('header', 'marketplace');
  }

  public function executeCart(sfWebRequest $request)
  {
    /** @var $shopping_cart ShoppingCart */
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    switch ($request->getParameter('cmd'))
    {
      case 'empty':
        // Delete all the cart Collectibles
        $shopping_cart
          ->getShoppingCartCollectibles()
          ->delete();

        $this->getUser()->setFlash('success', 'Your shopping cart was emptied!', true);
        $this->redirect('@shopping_cart');
        break;

      case 'remove':
        $c = new Criteria();
        $c->add(ShoppingCartCollectiblePeer::COLLECTIBLE_ID, $request->getParameter('id'));

        /** @var $cart_collectible ShoppingCartCollectible */
        if ($cart_collectible = $shopping_cart->getShoppingCartCollectibles($c)->getFirst())
        {
          $cart_collectible->delete();

          $this->getUser()->setFlash(
            'success', 'The item was successfully removed from your cart', true
          );
          $this->redirect('@shopping_cart');
        }
        else
        {
          $this->getUser()->setFlash(
            'error',
            'We could not remove the item from you shopping cart
             or it has been already removed!',
            true
          );
        }

        break;
    }

    if ($request->isMethod('post'))
    {
      $form = new CollectibleForSaleBuyForm();
      $form->bind($request->getParameter('collectible_for_sale'));

      if ($form->isValid())
      {
        $values = $form->getValues();

        /** @var $q CollectibleForSaleQuery */
        $q = CollectibleForSaleQuery::create()
           ->filterByCollectibleId($values['collectible_id']);

        if ($collectible_for_sale = $q->findOne())
        {
          try
          {
            $shopping_cart_collectible = new ShoppingCartCollectible();
            $shopping_cart_collectible->setCollectible($collectible_for_sale->getCollectible());
            $shopping_cart_collectible->setPriceAmount($collectible_for_sale->getPrice() * 1.00);
            $shopping_cart_collectible->setPriceCurrency('USD');
            $shopping_cart_collectible->setTaxAmount(0);
            $shopping_cart_collectible->setShippingCountryIso3166($this->getUser()->getCountryCode('US'));
            $shopping_cart_collectible->updateShippingFeeAmountFromCountryCode();

            $shopping_cart->addShoppingCartCollectible($shopping_cart_collectible);
            $shopping_cart->save();

            $this->getUser()->setFlash('success', $this->__('The item was added to your cart.'));

            $this->redirect('@shopping_cart');
          }
          catch (PropelException $e)
          {
            if (preg_match("/1062 Duplicate entry '(\d+)-(\d+)' for key 'PRIMARY'/i", $e->getMessage()))
            {
              $this->getUser()->setFlash(
                'success', 'This item was already in your cart!'
              );
            }
            else
            {
              throw $e;
            }
          }
        }
        else
        {
          $this->getUser()->setFlash(
            'error', 'We are sorry but there was a problem adding the item to your cart!'
          );
        }
      }
      else
      {
        $this->getUser()->setFlash(
          'error', 'We are sorry but there was a problem adding the item to your cart!'
        );
      }
    }

    if ($shopping_cart->countCollectibles() === 0)
    {
      return 'Empty';
    }

    $this->shopping_cart = $shopping_cart;
    $this->shopping_cart_collectibles = $shopping_cart->getShoppingCartCollectibles();

    return sfView::SUCCESS;
  }

  public function executeCheckout(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    if ($request->isMethod('post'))
    {
      $form = new ShoppingCartCollectibleCheckoutForm();
      $form->bind($request->getParameter('checkout'));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $shipping_address = new CollectorAddress();
        $shipping_address->setCountryIso3166($values['country_iso3166']);

        /** @var $collectible_for_sale CollectibleForSale */
        $collectible_for_sale = CollectibleForSaleQuery::create()
          ->findOneByCollectibleId($values['collectible_id']);

        if ($collectible_for_sale && !$collectible_for_sale->isForSale())
        {
          $this->getUser()->setFlash(
            'error', 'There was a problem processing the request
                      and we have removed the item from your cart.
                      Most probably this item has already been sold
                      or no longer available for purchase.'
          );

          // Remove the CollectibleForSale from the shopping cart
          $q = ShoppingCartCollectibleQuery::create()
            ->filterByCollectible($collectible_for_sale->getCollectible())
            ->filterByShoppingCart($shopping_cart);
          $q->delete();

          $this->redirect('@shopping_cart');
        }
        else if ($this->getCollector(false)->isOwnerOf($collectible_for_sale))
        {
          $this->getUser()->setFlash(
            'error', 'Sorry, you cannot buy your own items!'
          );
          $this->redirect('@shopping_cart');
        }

        /** @var $q ShoppingOrderQuery */
        $q = ShoppingOrderQuery::create()
           ->filterByShoppingCart($shopping_cart)
           ->filterByCollectibleId($collectible_for_sale->getCollectibleId());

        /** @var $shopping_order ShoppingOrder */
        $shopping_order = $q->findOneOrCreate();

        try
        {
          $shopping_order->setSellerId($collectible_for_sale->getCollectorId());
          $shopping_order->setCollectorId($this->getCollector()->getId());
          $shopping_order->setShippingAddress($shipping_address);
          $shopping_order->setNoteToSeller($values['note_to_seller']);
          $shopping_order->save();

          $this->redirect('@shopping_order_shipping?uuid='. $shopping_order->getUuid());
        }
        catch (Exception $e)
        {
          //  $this->getUser()->setFlash(
          //    'error', 'There was an error proceeding to the checkout screen'
          //  );

           return sfView::ERROR;
        }
      }
      else
      {
        $this->getUser()->setFlash(
          'error', 'There was an error proceeding to the checkout screen'
        );

        // return sfView::ERROR;
      }
    }

    $this->redirect('@shopping_cart');

    return sfView::NONE;
  }

  public function executeCheckoutStandard()
  {

  }

  public function executeOrderShipping(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    if ($this->getUser()->isAuthenticated() && $request->getParameter('address_id'))
    {
      $q = CollectorAddressQuery::create()
        ->filterById($request->getParameter('address_id'))
        ->filterByCollector($this->getCollector());

      if ($collector_address = $q->findOne())
      {
        $shopping_order->setShippingAddress($collector_address);
        $shopping_order->setShippingAddressId($request->getParameter('address_id'));
        $shopping_order->save();
      }
    }

    /**
     * Create the Shopping Order form
     */
    $form = new ShoppingOrderShippingForm($shopping_order);

    if ($request->isMethod('post') && '' !== $request->getParameter('new_address', null))
    {
      $form->bind($request->getParameter('shopping_order'));

      if ($form->isValid() && $form->save())
      {
        /**
         * We need to save the shipping address if the user is Authenticated
         */
        if ($this->getUser()->isAuthenticated())
        {
          $shipping_address = $form->getValue('shipping_address');
          if (!$shipping_address['address_id'])
          {
            unset($shipping_address['address_id']);

            $collector_address = new CollectorAddress();
            $collector_address->setCollector($this->getCollector());
            $collector_address->fromArray($shipping_address, BasePeer::TYPE_FIELDNAME);
            $collector_address->save();
          }
        }

        $this->redirect(
          'shopping_order_pay',
          array(
            'sf_subject' => $shopping_order,
            'encrypt' => 1
          ),
          302
        );
      }
      else if (!$form->getValue('shipping_address'))
      {
        $this->getUser()->setFlash(
          'error', 'Please choose an address on file or enter a new address.', false
        );
      }
    }

    if ($request->getParameter('shopping_order'))
    {
      $defaults = $request->getParameter('shopping_order');

      // If the user has selected to add new address we need to clear 'shipping_address' fields
      if ('' === $request->getParameter('new_address', null))
      {
        unset($defaults['shipping_address']);
      }

      $defaults['country_iso3166'] = $form->getDefault('country_iso3166');
      $form->setDefaults($defaults);
    }

    $this->form               = $form;
    $this->shopping_order     = $shopping_order;
    $this->shopping_payment   = $shopping_payment;
    $this->shipping_addresses = $this->getCollector()->getCollectorAddresses();

    return sfView::SUCCESS;
  }

  public function executeOrderPay(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    // Check if the Order is already completed and redirect appropriately
    if ($shopping_payment && $shopping_payment->getStatus() == ShoppingPaymentPeer::STATUS_COMPLETED)
    {
      $this->getUser()->setFlash('success', 'This order has already been paid');
      $this->redirect('shopping_order', $shopping_order);
    }

    if (null === $shopping_order->getShippingFeeAmount())
    {
      // cannot be shipped to selected country, abort
      $this->getUser()->setFlash(
        'error', 'The seller does not ship to the destination country you have selected.'
      );

      $this->redirect('shopping_order_shipping', $shopping_order);
    }

    switch (strtolower($request->getParameter('processor', 'paypal')))
    {
      case 'paypal':
      default:

        $shopping_payment = new ShoppingPayment();
        $shopping_payment->setCookieUuid($this->getUser()->getCookieUuid());
        $shopping_payment->setShoppingOrder($shopping_order);
        $shopping_payment->setProcessor(ShoppingPaymentPeer::PROCESSOR_PAYPAL);
        $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_INITIALIZED);
        $shopping_payment->save();

        $shopping_order->setShoppingPaymentId($shopping_payment->getId());
        $shopping_order->save();

        $PayRequestFields = $shopping_order->getPaypalPayRequestFields();
        $PayRequestFields['ReturnURL'] = $this->generateUrl(
          'shopping_order_paypal',
          array('uuid' => $shopping_order->getUuid(), 'cmd' => 'return', 'encrypt' => true),
          true
        );
        $PayRequestFields['CancelURL'] = $this->generateUrl(
          'shopping_order_paypal',
          array('uuid' => $shopping_order->getUuid(), 'cmd' => 'cancel', 'encrypt' => true),
          true
        );

        if ($domain = sfConfig::get('app_paypal_ipn_domain'))
        {
          $PayRequestFields['IPNNotificationURL'] = $domain . $this->generateUrl(
            'shopping_order_paypal',
            array('uuid' => $shopping_order->getUuid(), 'cmd' => 'ipn', 'encrypt' => true),
            false
          );
        }
        else
        {
          $PayRequestFields['IPNNotificationURL'] = $this->generateUrl(
            'shopping_order_paypal',
            array('uuid' => $shopping_order->getUuid(), 'cmd' => 'ipn', 'encrypt' => true),
            true
          );
        }

        $PayRequestFields['TrackingID'] = $shopping_payment->getTrackingId();

        $PayPalRequest = array(
          'PayRequestFields' => $PayRequestFields,
          'ClientDetailsFields' => $shopping_order->getPaypalClientDetailsFields(),
          'Receivers' => $shopping_order->getPaypalReceivers(),
          'SenderIdentifierFields' => $shopping_order->getPaypalSenderIdentifierFields(),
          'AccountIdentifierFields' => $shopping_order->getPaypalAccountIdentifierFields()
        );

        $shopping_payment->setPayPalPayRequest($PayPalRequest);
        $shopping_payment->save();

        $AdaptivePayments = cqStatic::getPayPalAdaptivePaymentsClient();
        $result = $AdaptivePayments->Pay($PayPalRequest);

        if ($AdaptivePayments->APICallSuccessful($result['Ack']))
        {
          $shopping_payment->setProperty('paypal.pay_key', $result['PayKey']);
          $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_INPROGRESS);
          $shopping_payment->save();

          $this->pay_key = $result['PayKey'];

          // Prepare request arrays
          $SPOFields = array(
            'PayKey' => $result['PayKey']
          );

          $SenderOptions = array(
            // If true, require the sender to select a shipping address
            // during the embedded payment flow. Default is false.
            'RequireShippingAddressSelection' => false
          );

          $InvoiceData = array(
            // Total tax associated with the payment.
            'TotalTax' => $shopping_order->getTaxAmount(),
            // Total shipping associated with the payment.
            'TotalShipping' => $shopping_order->getShippingFeeAmount('float'),
          );

          $PayPalRequest = array(
            'SPOFields' => $SPOFields,
            'DisplayOptions' => array(),
            'InstitutionCustomer' => array(),
            'SenderOptions' => $SenderOptions,
            'ReceiverOptions' => array(),
            'InvoiceData' => $InvoiceData,
            'InvoiceItems' => array(),
            'ReceiverIdentifier' => array()
          );

          // Pass data into class for processing with PayPal
          // and load the response array into $PayPalResult
          $result = $AdaptivePayments->SetPaymentOptions($PayPalRequest);

          if (!$AdaptivePayments->APICallSuccessful($result['Ack']))
          {
            $this->shopping_order   = $shopping_order;
            $this->shopping_payment = $shopping_payment;

            return sfView::ERROR;
          }
        }
        else if (isset($result['L_ERRORCODE0']) && $result['L_ERRORCODE0'] === '10412')
        {
          $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_FAILED);
          $shopping_payment->save();

          $this->getUser()->setFlash('error', 'You have already paid this order!');
          $this->redirect('@mycq_shopping_order?uuid='. $shopping_order->getUuid());
        }
        else
        {
          $shopping_payment->setProperty('paypal.error', serialize($result));
          $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_FAILED);
          $shopping_payment->save();

          $this->shopping_order   = $shopping_order;
          $this->shopping_payment = $shopping_payment;

          return sfView::ERROR;
        }

        break;
    }

    return sfView::SUCCESS;
  }

  public function executeOrderReview()
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    // Stop right here if not authorized to see this Shopping Order
    $this->forward404Unless($this->getUser()->isOwnerOf($shopping_order));

    // Check if the Order is already completed and redirect appropriately
    if (
      !$shopping_payment ||
      !in_array(
        $shopping_payment->getStatus(),
        array(ShoppingPaymentPeer::STATUS_CONFIRMED, ShoppingPaymentPeer::STATUS_COMPLETED)
      )
    )
    {
      $this->getUser()->setFlash(
        'error', sprintf('Order <b>%s</b> has not been paid yet!', $shopping_order->getUuid()), true
      );

      return $this->redirect(
        'shopping_order_pay',
        array(
          'sf_subject' => $shopping_order,
          'encrypt' => 1
        )
      );
    }
    else if ($shopping_payment->getStatus() === ShoppingPaymentPeer::STATUS_CONFIRMED)
    {
      $this->getUser()->setFlash(
        'highlight', '<strong>NOTICE:</strong> Your payment has been confirmed but not yet COMPLETE!
                      The seller will wait until the payment has cleared in their account before shipping the item.'
      );
    }

    $this->shopping_order   = $shopping_order;
    $this->shopping_payment = $shopping_payment;

    $this->collectible = $shopping_order->getCollectible();
    $this->seller = $shopping_order->getSeller();

    return sfView::SUCCESS;
  }

  public function executeOrderError()
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    $this->shopping_order   = $shopping_order;
    $this->shopping_payment = $shopping_payment;

    $this->setTemplate('orderPay', 'shopping');
    return sfView::ERROR;
  }

  public function executeOrder(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    if ($_shopping_order = ShoppingOrderPeer::retrieveByHash($request->getParameter('hash')))
    {
      $this->getUser()->setOwnerOf($_shopping_order);
    }

    if ($shopping_payment->getStatus() == ShoppingPaymentPeer::STATUS_COMPLETED)
    {
      if (
        $this->getUser()->isAuthenticated() &&
        (
          $this->getUser()->getId() === $shopping_order->getSellerId() ||
          $this->getUser()->getId() === $shopping_order->getCollectorId()
        )
      )
      {
        $this->redirect('mycq_collectible_by_slug', $shopping_order->getCollectible());
      }
      else if ($shopping_order->equals($_shopping_order))
      {
        $this->redirect(
          'shopping_order_review',
          array(
            'sf_subject' => $shopping_order,
            'encrypt' => 1
          )
        );
      }
    }

    $this->redirect('collectible_by_slug', $shopping_order->getCollectible());
  }

  public function executePaypal(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    /** @var $cmd string */
    $cmd = strtolower($request->getParameter('cmd'));

    switch ($cmd)
    {
      case 'return':

        $PayPalRequestData = array('PaymentDetailsFields' => array(
          'PayKey' => $shopping_payment->getProperty('paypal.pay_key'),
          'TrackingID' => $shopping_payment->getTrackingId()
        ));

        $AdaptivePayments = cqStatic::getPayPalAdaptivePaymentsClient();
        $result = $AdaptivePayments->PaymentDetails($PayPalRequestData);

        if (!$AdaptivePayments->APICallSuccessful($result['Ack']))
        {
          $this->url = '@shopping_order_error?uuid='. $shopping_order->getUuid();

          return 'Redirect';
        }

        $shopping_payment->setProperty('paypal.payment_details', serialize($result));
        $shopping_payment->setProperty('paypal.sender_email', $result['SenderEmail']);
        $shopping_payment->setProperty('paypal.status', $result['Status']);
        $shopping_payment->save();

        // Remove the CollectibleForSale from the shopping cart
        $q = ShoppingCartCollectibleQuery::create()
           ->filterByCollectible($shopping_order->getCollectible())
           ->filterByShoppingCart($shopping_order->getShoppingCart());
        $q->delete();

        // The Collectible has sold, so decrease the quantity (make zero)
        $shopping_order->getCollectibleForSale()->setQuantity(0);

        // The Collectible has sold, mark it as sold (legacy)
        $shopping_order->getCollectibleForSale()->setIsSold(true);
        $shopping_order->getCollectibleForSale()->save();

        // Add this order to the session if it's a guest checkout
        if (!$this->getUser()->isAuthenticated())
        {
          $orders = array_merge(
            $this->getUser()->getAttribute('orders', array(), 'shopping'),
            array($shopping_order->getUuid())
          );
          $this->getUser()->setAttribute('orders', $orders, 'shopping');

          // Set as the owner of this Shopping Order
          $this->getUser()->setOwnerOf($shopping_order);
        }

        if (strtoupper($result['Status']) === 'COMPLETED')
        {
          $this->orderComplete($shopping_order, $result['PaymentInfo']['TransactionID']);
        }
        else if (
          in_array(
            strtoupper($request->getParameter('payment_status')),
            array('CANCELED', 'VOIDED', 'DENIED', 'FAILED', 'REFUSED')
          )
        )
        {
          $this->orderFailed($shopping_order, $request->getParameter('txn_id'));
        }
        else if (
          in_array(
            strtoupper($request->getParameter('payment_status')),
            array('REFUNDED', 'REFUSED', 'REVERSED', 'UNCLAIMED', 'EXPIRED')
          )
        )
        {
          $this->orderRefunded($shopping_order, $request->getParameter('txn_id'));
        }
        else
        {
          $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_CONFIRMED);
          $shopping_payment->save();
        }

        /**
         * If the user is authenticated, let's send her straight to the
         * My CQ area and not duplicate functionality
         */
        $route = $this->getUser()->isAuthenticated() ?
          '@mycq_shopping_order' :
          '@shopping_order_review';

        $this->url = $route . '?uuid='. $shopping_order->getUuid();

        return 'Redirect';
        break;

      case 'cancel':

        $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_CANCELLED);
        $shopping_payment->save();

        $this->getUser()->setFlash(
          'error', 'You have not completed your purchase', true
        );
        $this->url = '@shopping_cart';

        return 'Redirect';
        break;

      case 'ipn':
        // We do not want the web debug bar on IPN requests
        sfConfig::set('sf_web_debug', false);

        $ipn = cqStatic::getPayPalIPNClient();

        try
        {
          $ipn->requirePostMethod();

          // Try to process the IPN post
          if ($ipn->processIpn())
          {
            if ('COMPLETED' === strtoupper($request->getParameter('payment_status')))
            {
              $this->orderComplete($shopping_order, $request->getParameter('txn_id'));
            }
            else if (
              in_array(
                strtoupper($request->getParameter('payment_status')),
                array('CANCELED', 'VOIDED', 'DENIED', 'FAILED', 'REFUSED')
              )
            )
            {
              $this->orderFailed($shopping_order, $request->getParameter('txn_id'));
            }
            else if (
              in_array(
                strtoupper($request->getParameter('payment_status')),
                array('REFUNDED', 'REFUSED', 'REVERSED', 'UNCLAIMED', 'EXPIRED')
              )
            )
            {
              $this->orderRefunded($shopping_order, $request->getParameter('txn_id'));
            }
            else
            {
              $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_CONFIRMED);
              $shopping_payment->save();
            }
          }
        }
        catch (Exception $e)
        {
          // Invalid IPN, we should not care about those requests
        }

        return sfView::NONE;
        break;
    }

    return sfView::ERROR;
  }

  /**
   * All actions for when the Shopping Order is complete
   *
   * @param ShoppingOrder $shopping_order
   * @param string $transaction_id
   */
  private function orderComplete(ShoppingOrder $shopping_order, $transaction_id = null)
  {
    $shopping_payment = $shopping_order->getShoppingPayment();
    $shopping_payment->setProperty('paypal.transaction_id', $transaction_id);
    $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_COMPLETED);
    $shopping_payment->save();

    // Remove the CollectibleForSale from the shopping cart
    $q = ShoppingCartCollectibleQuery::create()
      ->filterByCollectible($shopping_order->getCollectible())
      ->filterByShoppingCart($shopping_order->getShoppingCart());
    $q->delete();

    // The Collectible has sold, so decrease the quantity (make zero)
    $shopping_order->getCollectibleForSale()->setQuantity(0);

    // The Collectible has sold, mark it as sold (legacy)
    $shopping_order->getCollectibleForSale()->setIsSold(true);
    $shopping_order->getCollectibleForSale()->save();

    /**
     * Send emails to both the seller and the buyer
     */
    $cqEmail = new cqEmail($this->getMailer());
    $cqEmail->send('Shopping/buyer_order_confirmation', array(
      'to' => $shopping_order->getBuyerEmail(),
      'params' => array(
        'buyer_name'  => $shopping_order->getShippingFullName(),
        'oSeller' => $shopping_order->getSeller(),
        'oCollectible' => $shopping_order->getCollectible(),
        'oShoppingOrder' => $shopping_order
      )
    ));

    $cqEmail = new cqEmail($this->getMailer());
    $cqEmail->send('Shopping/seller_order_notification', array(
      'to' => $shopping_order->getSeller()->getEmail(),
      'params' => array(
        'buyer_name'  => $shopping_order->getShippingFullName(),
        'oSeller' => $shopping_order->getSeller(),
        'oCollectible' => $shopping_order->getCollectible(),
        'oShoppingOrder' => $shopping_order
      )
    ));
  }

  /**
   * All actions for when the Shopping Order has failed
   *
   * @param ShoppingOrder $shopping_order
   * @param string $transaction_id
   */
  private function orderFailed(ShoppingOrder $shopping_order, $transaction_id = null)
  {
    $shopping_payment = $shopping_order->getShoppingPayment();
    $shopping_payment->setProperty('paypal.transaction_id', $transaction_id);
    $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_FAILED);
    $shopping_payment->save();

    // The Collectible should go back for sale, so make the quantity 1
    $shopping_order->getCollectibleForSale()->setQuantity(1);

    // The Collectible should go back for sale, mark it as not sold (legacy)
    $shopping_order->getCollectibleForSale()->setIsSold(false);
    $shopping_order->getCollectibleForSale()->save();

//    $cqEmail = new cqEmail($this->getMailer());
//    $cqEmail->send('Shopping/buyer_order_failed', array(
//      'to' => $shopping_order->getBuyerEmail(),
//      'params' => array(
//        'buyer_name'  => $shopping_order->getShippingFullName(),
//        'oSeller' => $shopping_order->getSeller(),
//        'oCollectible' => $shopping_order->getCollectible(),
//        'oShoppingOrder' => $shopping_order
//      )
//    ));
  }

  /**
   * All actions for when the Shopping Payment has been refunded
   *
   * @param ShoppingOrder $shopping_order
   * @param string $transaction_id
   */
  private function orderRefunded(ShoppingOrder $shopping_order, $transaction_id = null)
  {
    $shopping_payment = $shopping_order->getShoppingPayment();
    $shopping_payment->setProperty('paypal.transaction_id', $transaction_id);
    $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_CANCELLED);
    $shopping_payment->save();

    // The Collectible should go back for sale, so make the quantity 1
    $shopping_order->getCollectibleForSale()->setQuantity(1);

    // The Collectible should go back for sale, mark it as not sold (legacy)
    $shopping_order->getCollectibleForSale()->setIsSold(false);
    $shopping_order->getCollectibleForSale()->save();

//    $cqEmail = new cqEmail($this->getMailer());
//    $cqEmail->send('Shopping/buyer_order_refunded', array(
//      'to' => $shopping_order->getBuyerEmail(),
//      'params' => array(
//        'buyer_name'  => $shopping_order->getShippingFullName(),
//        'oSeller' => $shopping_order->getSeller(),
//        'oCollectible' => $shopping_order->getCollectible(),
//        'oShoppingOrder' => $shopping_order
//      )
//    ));
  }
}
