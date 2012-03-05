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
        $PayPal = new PayPal(array(
          'APIUsername' => 'kangov_1327417143_biz_api1.collectorsquest.com',
          'APIPassword' => '1327417177',
          'APISignature' => 'A..-CRlw0YpkY2B3ut0vv.OPym-eAT-VZrXVgkU8OKXkg-3ddWsYS50Q'
        ));

        $SECFields = array(
          'maxamt' => '200.00',
          'returnurl' => $this->generateUrl('shopping_order_paypal', array('uuid' => $shopping_order->getUuid(), 'cmd' => 'return', 'encrypt' => 1), true),
          'cancelurl' => $this->generateUrl('shopping_order_paypal', array('uuid' => $shopping_order->getUuid(), 'cmd' => 'cancel', 'encrypt' => 1), true),
          'reqconfirmshipping' => '1',
          'noshipping' => '0',
          'allownote' => '1',
          'addroverride' => '1',
          'localecode' => 'en',
          'skipdetails' => '1',
          'email' => '', 								// Email address of the buyer as entered during checkout.  PayPal uses this value to pre-fill the PayPal sign-in page.  127 char max.
          'solutiontype' => 'Mark',
          'landingpage' => 'Billing',
          'channeltype' => 'Merchant',
          'brandname' => 'CollectorsQuest.com',
          'customerservicenumber' => '555-555-5555',
          'giftmessageenable' => '0',
          'giftreceiptenable' => '0',
          'giftwrapenable' => '0',
          'buyeremailoptionenable' => '0',
          'surveyenable' => '0',
          'allowpushfunding' => '0'
        );

        // Basic array of survey choices.  Nothing but the values should go in here.
        $SurveyChoices = array('Yes', 'No');

        $Payments = array();
        $Payment = array(
          'amt' => $shopping_order->getTotalAmount() + $shopping_order->getShippingAmount(),
          'currencycode' => $shopping_order->getCurrency(),
          'itemamt' => $shopping_order->getTotalAmount(),
          'shippingamt' => $shopping_order->getShippingAmount(),
          'desc' => $shopping_order->getDescription(),
          'custom' => '', 						// Free-form field for your own use.  256 char max.
          'invnum' => $shopping_order->getUuid(),
          'notifyurl' => $this->generateUrl('shopping_order_paypal', array('uuid' => $shopping_order->getUuid(), 'cmd' => 'ipn', 'encrypt' => 1), true),
          'notetext' => $shopping_order->getNoteToSeller(),
          'allowedpaymentmethod' => 'InstantPaymentOnly',
          'paymentaction' => 'Sale'
        );

        $PaymentOrderItems = array();

        /** @var $collectible_for_sale CollectibleForSale */
        $collectible_for_sale = $shopping_order->getCollectibleForSale();

        /** @var $collectible Collectible */
        $collectible = $collectible_for_sale->getCollectible();

        $Item = array(
          'name' => $collectible->getName(),
          'desc' => $collectible->getDescription('stripped', 127),
          'amt' => $collectible_for_sale->getPrice(),
          'number' => $collectible_for_sale->getId(),
          'qty' => '1',
          'taxamt' => '',
          'itemurl' => $this->generateUrl('collectible_by_slug', array('id' => $collectible->getId(), 'slug' => $collectible->getSlug()))
        );
        array_push($PaymentOrderItems, $Item);

        $Payment['order_items'] = $PaymentOrderItems;
        array_push($Payments, $Payment);

        $PayPalRequest = array(
          'SECFields' => $SECFields,
          'SurveyChoices' => $SurveyChoices,
          'Payments' => $Payments
        );

        $result = $PayPal -> SetExpressCheckout($PayPalRequest);

        if ($result['ACK'] === 'Success')
        {
          $this->redirect($result['REDIRECTURL'], 302);
        }

        dd($result);
      break;
    }
  }

  public function executePaypal(sfWebRequest $request)
  {
    $shopping_order = $this->getRoute()->getObject();
    $cmd = $request->getParameter('cmd');

    switch (strtolower($cmd))
    {
      case 'return':
        break;
      case 'cancel':
        break;
      case 'ipn':
        break;
    }

    return sfView::SUCCESS;
  }

}
