<?php

/**
 * @package    CollectorsQuest
 * @subpackage seller
 * @author     Collectors
 */
class sellerActions extends cqFrontendActions
{

  public function preExecute()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');
  }

  public function executeIndex()
  {
    return $this->redirect('@mycq_marketplace');
  }

  public function executeStore(sfWebRequest $request)
  {
    /* @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    if ($request->getParameter('legacy'))
    {
      $this->redirect('collector_by_slug', $collector, 301);
    }

    /* @var $aetn_shows array */
    $aetn_shows = sfConfig::get('app_aetn_shows', array());
    $aetn_collector_id = $aetn_shows['american_pickers']['collector'];
    if ($collector->getId() == $aetn_collector_id)
    {
      $this->redirect('@aetn_franks_picks', 301);
    }

    $for_sale_ids = FrontendCollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->isForSale()
      ->select('CollectibleId')
      ->find()->getArrayCopy();

    /* @var $q FrontendCollectionCollectibleQuery */
    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollector($collector)
      ->filterByCollectibleId($for_sale_ids, Criteria::IN)
      ->groupBy('CollectionCollectible.CollectibleId')
      ->joinWith('CollectionCollectible.Collectible')
      ->joinWith('Collectible.CollectibleForSale');

    if ($collection_id = $request->getParameter('collection_id'))
    {
      $q->filterByCollectionId($collection_id);
      $this->collection_id = $collection_id;

      // Make the collection_id available to the sidebar
      $this->setComponentVar('collection_id', $collection_id, 'sidebarCollectorList');
    }

    $pager = new PropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    SmartMenu::setSelected('collectibles_for_collector_list', 'for_sale');
    $store_name = $collector->getSeller()->getSellerSettingsStoreName();
    $this->title = $store_name ?: $collector->getDisplayName() ."'s Store";
    $this->addBreadcrumb($store_name);

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl(
      $this->generateUrl('seller_store', array('sf_subject' => $collector))
    );

    $this->collector = $collector;
    $this->pager = $pager;
  }

  public function executeSignup()
  {
    $this->redirectIf($this->getUser()->isAuthenticated(), '@seller_packages');

    return sfView::SUCCESS;
  }

  /**
   * Action Packages
   *
   * @param sfWebRequest $request
   *
   * @throws Exception
   * @return string
   */
  public function executePackages(sfWebRequest $request)
  {
    // if the user is not authenticated, display a signup form
    if (!$this->getUser()->isAuthenticated())
    {
      $this->signupForm = new SellerPackagesSignupForm();

      if ($request->isMethod('post'))
      {
        $this->signupForm->bind($request->getParameter($this->signupForm->getName()));
        if ($this->signupForm->isValid())
        {
          $values = $this->signupForm->getValues();
          // try to guess the collector's country based on IP address
          $values['country_iso3166'] = cqStatic::getGeoIpCountryCode(
            $request->getRemoteAddress(), $check_against_geo_country = true
          );

          // Run the pre create hook
          $this->getUser()->preCreateHook();

          // create the collector
          $collector = CollectorPeer::createFromArray($values);

          // Run the post create hook
          $this->getUser()->postCreateHook($collector);

          // authenticate the collector
          $this->getUser()->Authenticate(true, $collector, false);

          // add a succesfully created user flash
          $this->getUser()->setFlash('success', sprintf(
            'Congradulations %s, you have successfully signed up for your new CollectorsQuest account',
            $collector->getDisplayName()
          ));

          // unset signup form after user registration, it's not needed anymore
          $this->signupForm = null;
        }
      }
    }

    $packagesForm = new SellerPackagesForm();
    $packagesForm->setDefault('package_id', $request->getParameter('package'));

    if ($request->isMethod('post'))
    {
      // Apply Discount button was clicked
      if ('applyPromo' == $request->getParameter('applyPromo', false))
      {
        $packagesForm->setPartialRequirements();
        $packagesForm->bind($request->getParameter($packagesForm->getName()));

        if ($packagesForm->isValid())
        {
          // expose promotion for the template
          $this->promotion = $packagesForm->getPromotion();

          if ($this->promotion && $package = $packagesForm->getPackage())
          {
            if (0 == $package->getPriceWithDiscount($this->promotion))
            {
              $this->discount_message = 'Free Subscription!';
            }
            else
            {
              $this->discount_message = sprintf(
                '%d%s discount',
                $this->promotion->getAmount(),
                $this->promotion->getAmountTypeString()
              );
            }
          }
        }
      }
      // Normal submit button was clicked
      else
      {
        $packages_request = $request->getParameter($packagesForm->getName());
        $packagesForm->bind($packages_request);

        // this is needed so we can display the appropriate pricing even when
        // there is an error in the payment part of the form; Validation for the
        // field is unrelated and will still be executed
        $this->promotion = PromotionQuery::create()->findOneByPromotionCode(
          trim($packages_request['promo_code']) // propel escapes the value for us
        );

        if ($packagesForm->isValid() && $this->getUser()->isAuthenticated())
        {
          $promotion = $packagesForm->getPromotion();
          $package = $packagesForm->getPackage();
          $collector = $this->getUser()->getCollector();

          $transaction = PackageTransactionPeer::newTransaction($collector, $package, $promotion);

          //2. Save transaction
          if (0 == $transaction->getPackagePrice())
          {
            //2.1 If free - apply collector changes
            $transaction->confirmPayment();

            $cqEmail = new cqEmail($this->getMailer());
            $sent = $cqEmail->send('Seller/package_confirmation', array(
              'to'     => $collector->getEmail(),
              'params' => array(
                'collector' => $collector,
                'package_transaction' => $transaction,
                'package' => $package,
              ),
            ));

            if (!$sent)
            {
              $this->logMessage(sprintf(
                 'Email about package confirmation to %s not sent', $collector->getEmail()
              ));
            }

            $this->getUser()->setFlash(
              'success', 'Congratulations! You have received a free subscription!'
            );

            return $this->redirect('@mycq_marketplace_settings');
          }
          else if ('paypal' == $packagesForm->getValue('payment_type'))
          {
            //2.2. If paypal - redirect to paypal
            $this->packageTransaction = $transaction;
            $this->promotion = $promotion;

            $this->return_url = $this->generateUrl(
              'seller_payment_paypal',
              array('id' => $transaction->getId(), 'cmd' => 'return', 'encrypt' => true, 'lifetime' => 86400),
              true
            );

            $this->cancel_return_url = $this->generateUrl(
              'seller_payment_paypal',
              array('id' => $transaction->getId(), 'cmd' => 'cancel', 'encrypt' => true, 'lifetime' => 86400),
              true
            );

            if ($domain = sfConfig::get('app_paypal_ipn_domain'))
            {
              $this->notify_url = $domain . $this->generateUrl(
                'seller_payment_paypal',
                array('id' => $transaction->getId(), 'cmd' => 'ipn', 'encrypt' => true, 'lifetime' => 0),
                false
              );
            }
            else
            {
              $this->notify_url = $this->generateUrl(
                'seller_payment_paypal',
                array('id' => $transaction->getId(), 'cmd' => 'ipn', 'encrypt' => true, 'lifetime' => 0),
                true
              );
            }

            $this->setLayout('layout-minimal');

            return 'RedirectToPaypal';
          }
          else if ('cc' == $packagesForm->getValue('payment_type'))
          {
            // 2.3. If cc - try to pay
            // @todo replace cc payment with method
            // @todo cc payment

            $paypalAPI = cqStatic::getPayPalClient();

            $directPaymentFields = array(
              'paymentaction'    => 'Sale', // How you want to obtain payment. Default is Sale.
              'ipaddress'        => $request->getRemoteAddress(),
              'returnfmfdetails' => '1', // Whether you want the results returned by FMF. Default is 0.
            );

            $creditCardDetails = array(
              'creditcardtype' => $packagesForm->getValue('cc_type'),
              'acct'           => $packagesForm->getValue('cc_number'),
              'expdate'        => date('mY', strtotime($packagesForm->getValue('expiry_date'))),
              'cvv2'           => $packagesForm->getValue('cvv_number'),
            );

            $payerInfo = array(
              'email' => $collector->getEmail(),
            );

            $payerName = array(
              'firstname' => $packagesForm->getValue('first_name'),
              'lastname'  => $packagesForm->getValue('last_name'),
            );

            $billingAddressInfo = array(
              'street'      => $packagesForm->getValue('street'), // Required.  First street address.
              'street2'     => '', //Second street address.
              'city'        => $packagesForm->getValue('city'), // Required.  Name of City.
              'state'       => $packagesForm->getValue('state'), // Required. Name of State or Province.
              'countrycode' => $packagesForm->getValue('country'), // Required.  Country code.
              'zip'         => $packagesForm->getValue('zip'), // Required.  Postal code of payer.
              'phonenum'    => '' //Phone Number of payer.  20 char max.
            );

            $paymentDetails = array(
              // Required.  Total amount of order, including shipping, handling, and tax.
              'amt'          => $transaction->getPackagePrice(),
              // Required.  Three-letter currency code.  Default is USD.
              'currencycode' => sfConfig::get('app_paypal_currency_code', 'USD'),
              // Required if you include itemized cart details. Subtotal of items not including S&H, or tax.
              'itemamt'      => $transaction->getPackagePrice(),
              // Total shipping costs for the order. If you specify shippingamt, you must also specify itemamt.
              'shippingamt'  => 0.00,
              // Total handling costs for the order. If you specify handlingamt, you must also specify itemamt.
              'handlingamt'  => 0.00,
              // Sum of tax for all items on the order. Total sales tax.
              'taxamt'       => 0.00,
              // Description of the order the customer is purchasing.  127 char max.
              'desc'         => sprintf('Package %s order', $package->getPackageName()),
              'invnum'       => $transaction->getId(), // Your own invoice or tracking number
              'notifyurl'    => $this->generateUrl(
                'seller_payment_paypal',
                array('id' => $transaction->getId(), 'cmd' => 'ipn', 'encrypt' => true),
                true
              )
            );

            $orderItems = array(
              array(
                'l_name'    => $package->getPackageName(), // Item Name.  127 char max.
                'l_amt'     => $transaction->getPackagePrice(), // Cost of individual item.
                'l_number'  => $package->getId(), // Item Number.  127 char max.
                'l_qty'     => '1', // Item quantity.  Must be any positive integer.
                'l_taxamt'  => 0, // Item's sales tax amount.
              )
            );

            $paypalResult = $paypalAPI->DoDirectPayment(array(
              'DPFields'       => $directPaymentFields,
              'CCDetails'      => $creditCardDetails,
              'PayerInfo'      => $payerInfo,
              'PayerName'      => $payerName,
              'BillingAddress' => $billingAddressInfo,
              'PaymentDetails' => $paymentDetails,
              'OrderItems'     => $orderItems,
            ));

            if ('SUCCESS' === strtoupper($paypalResult['ACK']))
            {
              // Save package information with payment status paid while payment successfully done.
              $transaction->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID);
              $transaction->setPackagePrice($paypalResult['AMT']);
              $transaction->save();

              $collector->setUserType(CollectorPeer::TYPE_SELLER);
              $collector->save();

              // Send Mail To Seller
              $cqEmail = new cqEmail($this->getMailer());
              $cqEmail->send('Seller/package_confirmation', array(
                'to'     => $collector->getEmail(),
                'params' => array(
                  'collector' => $collector,
                  'package_transaction' => $transaction
                ),
              ));

              $this->getUser()->setFlash(
                'success', 'Thank you for your payment!
                            Please, enter your PayPal Account details and start listing your items for sale.'
              );

              return $this->redirect('@mycq_marketplace_settings');
            }
            else
            {
              $this->getUser()->setFlash(
                'error', 'Your payment could not be processed.
                          Please check your credit card information and try again.'
              );

              $this->packagesForm = $packagesForm;

              return sfView::SUCCESS;
            }
          }
          //2.4. Not supported payment method used
          else
          {
            //TODO: replace this with test
            throw new Exception(sprintf(
              'Invalid payment type %s', $packagesForm->getValue('payment_type')
            ));
          }
        }
      }
    }

    if ($request->getParameter('return_to'))
    {
      $this->getUser()->setAttribute('purchase_credits_return_to', $request->getParameter('return_to'), 'seller');
    }
    $this->packagesForm = $packagesForm;

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

    $AdaptivePayments = cqStatic::getPayPalAdaptivePaymentsClient();
    $result = $AdaptivePayments->GetShippingAddress($PayPalRequestData);

    dd($result);

    return sfView::SUCCESS;
  }

  public function executePaypal(sfWebRequest $request)
  {
    /** @var $package_transaction PackageTransaction */
    $package_transaction = $this->getRoute()->getObject();

    /** @var $cmd string */
    $cmd = strtolower($request->getParameter('cmd'));

    switch ($cmd)
    {
      case 'return':

        if ($package_transaction->getPaymentStatus() === PackageTransactionPeer::PAYMENT_STATUS_PENDING)
        {
          $package_transaction->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PROCESSING);
          $package_transaction->save();

          $this->getUser()->setFlash(
            'success',
            'You payment is currently being processed.
             Please take the time to update your store information below.',
            true
          );
        }
        else if ($package_transaction->getPaymentStatus() === PackageTransactionPeer::PAYMENT_STATUS_PAID)
        {
          $this->getUser()->setFlash(
            'success',
            'Thank you for your payment!
             You can now start listing items for sale in the Market.',
            true
          );
        }
        else
        {
          $this->getUser()->setFlash(
            'error',
            'There was a problem processing your PayPal payment.
             Please contact our customer support for assistance!',
            true
          );
        }

        return $this->redirect('@mycq_marketplace_settings');
        break;

      case 'cancel':

        $package_transaction->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_CANCELLED);
        $package_transaction->save();

        $this->getUser()->setFlash(
          'error', 'You did not complete your payment!', true
        );

        return $this->redirect('@seller_packages');
        break;

      case 'ipn':

        try
        {
          // Intantiate the IPN listener
          $ipn = cqStatic::getPayPalIPNClient();

          // Check if the request is a valid POST
          $ipn->requirePostMethod();

          // Finally process the IPN and see if it is valid
          $verified = $ipn->processIpn();
        }
        catch (Exception $e)
        {
          $verified = false;
        }

        if ($verified && 'COMPLETED' === strtoupper($request->getParameter('payment_status')))
        {
          $collector = $package_transaction->getCollector();
          $collector->setUserType(CollectorPeer::TYPE_SELLER);
          $collector->save();

          $package_transaction->setPackagePrice($request->getParameter('mc_gross'));
          $package_transaction->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID);
          $package_transaction->save();

          if (!$collector->getSellerSettingsPaypalEmail())
          {
            $collector->setSellerSettingsPaypalAccountStatus(strtoupper($request->getParameter('payer_status')));
            $collector->setSellerSettingsPaypalAccountId($request->getParameter('payer_id'));
            $collector->setSellerSettingsPaypalBusinessName('');
            $collector->setSellerSettingsPaypalEmail($request->getParameter('payer_email'));
            $collector->setSellerSettingsPaypalFirstName($request->getParameter('first_name'));
            $collector->setSellerSettingsPaypalLastName($request->getParameter('last_name'));
            $collector->save();
          }

          // Send Email To Seller
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('Seller/package_confirmation', array(
            'to'     => $collector->getEmail(),
            'params' => array(
              'collector' => $collector,
              'package_transaction' => $package_transaction
            ),
          ));

          // Deduct Number of time used promo code.
          if (
            $request->getParameter('custom', false) &&
            ($promotion = PromotionPeer::retrieveByPK($request->getParameter('custom')))
          )
          {
            $promotion->setNoOfTimeUsed($promotion->getNoOfTimeUsed() - 1);
            $promotion->save();
          }
        }

        return sfView::NONE;
        break;
    }

    return sfView::ERROR;
  }
}
