<?php

class shoppingActions extends cqActions
{
  public function executeCart(sfWebRequest $request)
  {
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
        $c->add(ShoppingCartCollectiblePeer::COLLECTIBLE_FOR_SALE_ID, $request->getParameter('id'));

        /** @var $cart_collectible ShoppingCartCollectible */
        if ($cart_collectible = $shopping_cart->getShoppingCartCollectibles($c)->getFirst())
        {
          $cart_collectible->delete();

          $this->getUser()->setFlash('success', 'We have removed the item from your cart!', true);
          $this->redirect('@shopping_cart');
        }
        else
        {
          $this->getUser()->setFlash('error', 'We could not remove the item from you shopping cart or it has been already removed!', true);
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
           ->filterById($values['id'])
           ->filterByCollectibleId($values['collectible_id']);

        if ($collectible_for_sale = $q->findOne())
        {
          try
          {
            $shopping_cart_collectible = new ShoppingCartCollectible();
            $shopping_cart_collectible->setCollectibleForSale($collectible_for_sale);
            $shopping_cart_collectible->setPrice($collectible_for_sale->getPrice());
            $shopping_cart->addShoppingCartCollectible($shopping_cart_collectible);
            $shopping_cart->save();

            $this->getUser()->setFlash('success', $this->__('The collectible was added to your cart.'));

            return $this->redirect('@shopping_cart');
          }
          catch (PropelException $e)
          {
            if (preg_match("/1062 Duplicate entry '(\d+)-(\d+)' for key 'PRIMARY'/i", $e->getMessage()))
            {
              $this->getUser()->setFlash('success', $this->__('This collectible was already in your cart!'));
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
            'error', $this->__('We are sorry but there was a problem adding the collectible to your cart!')
          );
        }
      }
      else
      {
        $this->getUser()->setFlash(
          'error', $this->__('We are sorry but there was a problem adding the collectible to your cart!')
        );
      }
    }

    $this->addBreadcrumb($this->__('Shopping Cart'), '@shopping_cart');

    if ($shopping_cart->countCollectibleForSales() === 0)
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

        $shopping_order = new ShoppingOrder();
        $shopping_order->setSessionId(session_id());
        $shopping_order->setShoppingCart($shopping_cart);
        $shopping_order->setCollectibleForSaleId($values['collectible_for_sale_id']);
        $shopping_order->setCollector($this->getCollector());
        $shopping_order->setNoteToSeller($values['']);
        $shopping_order->save();

        $this->redirect('@shopping_order_pay?uuid='. $shopping_order->getUuid() .'&processor=paypal');
      }
      else
      {
        return sfView::ERROR;
      }
    }

    return $this->redirect('@shopping_cart');
  }

  public function executeCheckoutStandard()
  {

  }

  public function executePay(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    switch (strtolower($request->getParameter('processor', 'paypal')))
    {
      case 'paypal':
      default:

        $shopping_payment = new ShoppingPayment();
        $shopping_payment->setSessionId(session_id());
        $shopping_payment->setShoppingOrderId($shopping_order->getId());
        $shopping_payment->setProcessor(ShoppingPaymentPeer::PROCESSOR_PAYPAL);
        $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_INITIALIZED);
        $shopping_payment->save();

        $SECFields = $shopping_order->getPaypalSECFields();
        $SECFields['returnurl'] = $this->generateUrl(
          'shopping_order_paypal',
          array('uuid' => $shopping_order->getUuid(), 'cmd' => 'return', 'encrypt' => 1),
          true
        );
        $SECFields['cancelurl'] = $this->generateUrl(
          'shopping_order_paypal',
          array('uuid' => $shopping_order->getUuid(), 'cmd' => 'cancel', 'encrypt' => 1),
          true
        );

        $shopping_payment->setProperty('paypal.sec_fields', serialize($SECFields));

        $Payments = $shopping_order->getPaypalPayments();
        $Payments[0]['notifyurl'] = $this->generateUrl(
          'shopping_order_paypal',
          array('uuid' => $shopping_order->getUuid(), 'cmd' => 'ipn', 'encrypt' => 1),
          true
        );

        $shopping_payment->setProperty('paypal.payments', serialize($Payments));
        $shopping_payment->save();

        $PayPalRequest = array(
          'SECFields' => $SECFields,
          'Payments' => $Payments,
          'SurveyChoices' => array('Yes', 'No')
        );

        $PayPal = cqStatic::getPayPalClient();
        $result = $PayPal->SetExpressCheckout($PayPalRequest);

        if (strtolower($result['ACK']) == 'success')
        {
          $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_INPROGRESS);
          $shopping_payment->save();

          $this->redirect($result['REDIRECTURL'], 302);
        }
        else
        {
          $shopping_payment->setStatus(ShoppingPaymentPeer::STATUS_FAILED);
          $shopping_payment->save();
        }

        return sfView::ERROR;
        break;
    }
  }

  public function executePaypal(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $cmd string */
    $cmd = $request->getParameter('cmd');

    switch (strtolower($cmd))
    {
      case 'return':
        $PayPal = cqStatic::getPayPalClient();
        $result = $PayPal->GetExpressCheckoutDetails($request->getParameter('token'));

        if ($result['ACK'] == 'Success' && $result['PAYERID'] == $request->getParameter('PayerID'))
        {
          $DECPFields = $shopping_order->getPaypalDECFields($result['TOKEN'], $result['PAYERID']);
          $Payments = $shopping_order->getPaypalPayments();

          $PayPalRequest = array(
            'DECPFields' => $DECPFields,
            'Payments' => $Payments
          );

          $result = $PayPal->DoExpressCheckoutPayment($PayPalRequest);
          dd($result);
        }
        else
        {
          return sfView::ERROR;
        }

        break;
      case 'cancel':
        break;
      case 'ipn':
        break;
    }

    return sfView::SUCCESS;
  }

}
