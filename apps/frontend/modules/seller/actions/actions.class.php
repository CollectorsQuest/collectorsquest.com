<?php

/**
 * seller actions.
 *
 * @package    CollectorsQuest
 * @subpackage seller
 * @author     Collectors
 */
class sellerActions extends cqFrontendActions
{
  public function preExecute()
  {
    $this->redirectUnless(IceGateKeeper::open('mycq_marketplace'), '@mycq');
  }

  public function executeIndex()
  {
    $this->redirect('@mycq_marketplace');
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
    $packagesForm = new SellerPackagesForm();

    if (sfRequest::POST == $request->getMethod())
    {
      if ('applyPromo' == $request->getParameter('submit', false))
      {
        $packagesForm->setPartialRequirements();
        $packagesForm->bind($request->getParameter($packagesForm->getName()));

        //        echo '<pre>';
        //        var_dump($packagesForm->isValid(), $packagesForm->getErrorSchema()); die();

        if ($packagesForm->isValid())
        {
          $promotion          = $packagesForm->getPromotion();
          $package            = $packagesForm->getPackage();
          $afterDiscountPrice = $package->getPackagePrice();

          if ($promotion)
          {
            $package->applyPromo($promotion);
            $afterDiscountPrice = $package->getPackagePrice() - $package->getDiscount();
            $freeSubscription   = (bool)($afterDiscountPrice <= 0);

            if ($freeSubscription)
            {
              $this->discountMessage = 'Free Subscription!';
            }
            else
            {
              $this->discountMessage = sprintf('%d%s discount', $promotion->getAmount(),
                PromotionPeer::DISCOUNT_FIXED == $package->getDiscountType() ? '$' : '%');
            }
          }
        }
      }
      else
      {
        $packagesForm->bind($request->getParameter($packagesForm->getName()));

        //        dd($request->getParameterHolder()->getAll(), $packagesForm->getErrorSchema()->getErrors());
        if ($packagesForm->isValid())
        {
          $promotion = $packagesForm->getPromotion();
          $package   = $packagesForm->getPackage();
          $collector = $this->getUser()->getCollector();

          $transaction = PackageTransactionPeer::newTransaction($collector, $package, $promotion);
          //          dd($transaction);

          //2. Save transaction
          if (0 > $transaction->getPackagePrice())
          {
            //2.1 If free - apply collector changes
            $transaction->confirmPayment();

            $cqEmail = new cqEmail($this->getMailer());
            $sent    = $cqEmail->send('Seller/package_confirmation', array(
              'to'     => $collector->getEmail(),
              'subject'=> 'Thank you for becoming a seller',
              'params' => array(
                'collector'     => $collector,
                'package_name'  => $package->getPackageName(),
                'package_items' => $package->getMaxItemsForSale() < 0 ? 'Unlimited' : $package->getMaxItemsForSale(),
                'expiry_date'   => strtotime('+1 year'),
              ),
            ));

            if (!$sent)
            {
              $this->logMessage(sprintf('Email about package confirmation to %s not sent', $collector->getEmail()));
            }

            $this->getUser()->setFlash('success', 'You received free subscription');
            $this->redirect('@mycq');
          }
          else if ('paypal' == $packagesForm->getValue('payment_type'))
          {
            //2.2. If paypal - redirect to paypal
            $this->packageTransaction = $transaction;
            $this->promotion          = $promotion;
            $this->setTemplate('redirect');

            return sfView::SUCCESS;
          }
          else if ('cc' == $packagesForm->getValue('payment_type'))
          {
            //2.3. If cc - try to pay
            //@todo replace cc payment with method
            //@todo cc payment

            $paypalAPI = cqStatic::getPayPalClient();

            $directPaymentFields = array(
              'paymentaction'    => 'Sale', // How you want to obtain payment.  Authorization indidicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
              'ipaddress'        => $request->getRemoteAddress(),
              'returnfmfdetails' => '1', // Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
            );

            $creditCardDetails = array(
              'creditcardtype' => $packagesForm->getValue('cc_type'),
              'acct'           => $packagesForm->getValue('cc_number'),
              'expdate'        => date('mY', strtotime($packagesForm->getValue('expiry_date'))),
                'cvv2'           => $packagesForm->getValue('cvv_number'),
            );

            $payerInfo = array(
              'email'       => $collector->getEmail(),
            );

            $payerName = array(
              'firstname' => $packagesForm->getValue('first_name'),
              'lastname' => $packagesForm->getValue('last_name'),
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
              'amt'          => $transaction->getPackagePrice(), // Required.  Total amount of order, including shipping, handling, and tax.
              'currencycode' => sfConfig::get('app_paypal_currency_code', 'USD'), // Required.  Three-letter currency code.  Default is USD.
              'itemamt'      => $transaction->getPackagePrice(), // Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
              'shippingamt'  => 0.00, // Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
              'handlingamt'  => 0.00, // Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
              'taxamt'       => 0.00, // Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax.
              'desc'         => sprintf('Package %s order', $package->getPackageName()), // Description of the order the customer is purchasing.  127 char max.
              'invnum'       => $transaction->getId(), // Your own invoice or tracking number
              'notifyurl'    => $this->generateUrl('seller_callback_ipn', array(), true) // URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
            );

            $orderItems = array(
              array(
                'l_name'                 => $package->getPackageName(), // Item Name.  127 char max.
                'l_amt'                  => $transaction->getPackagePrice(), // Cost of individual item.
                'l_number'               => $package->getId(), // Item Number.  127 char max.
                'l_qty'                  => '1', // Item quantity.  Must be any positive integer.
                'l_taxamt'               => 0, // Item's sales tax amount.
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

            if ('SUCCESS' == strtoupper($paypalResult["ACK"]))
            {
              // Save package information with payment status paid while payment successfully done.
              $transaction->setPaymentStatus(PackageTransactionPeer::STATUS_PAID);
              $transaction->setPackagePrice($paypalResult['AMT']);
              $transaction->save();

              $collector->setUserType(CollectorPeer::TYPE_SELLER);
              $collector->setItemsAllowed($package->getMaxItemsForSale());
              $collector->save();

              // Send Mail To Seller
              //@todo send email on success
              $cqEmail = new cqEmail($this->getMailer());
              $sent    = $cqEmail->send('Seller/package_confirmation', array(
                'to'     => $collector->getEmail(),
                'subject'=> 'Thank you for becoming a seller',
                'params' => array(
                  'collector'     => $collector,
                  'package_name'  => $package->getPackageName(),
                  'package_items' => $package->getMaxItemsForSale() < 0 ? 'Unlimited' : $package->getMaxItemsForSale(),
                  'expiry_date'   => strtotime('+1 year'),
                ),
              ));

              if (!$sent)
              {
                $this->logMessage(sprintf('Email about package confirmation to %s not sent', $collector->getEmail()));
              }

              $this->getUser()->setFlash('success', 'Payment received');
              $this->redirect('@mycq');
            }
            else
            {
              $this->sendEmail(sfConfig::get('app_ice_libs_emails_notify'), 'CC DEBUG', var_export($paypalResult, true));

              $this->getUser()->setFlash('error', 'Your credit card information is invalid!');

              $this->packagesForm = $packagesForm;
              return sfView::SUCCESS;
            }
            //@todo redirect on success

          }
          //2.4. Not supported payment method used
          else
          {
            //TODO: replace this with test
            throw new Exception(sprintf('Invalid payment type %s', $packagesForm->getValue('payment_type')));
          }
        }
      }
    }

    $this->packagesForm = $packagesForm;
    return sfView::SUCCESS;
  }

  /**
   * Action CancelPayment
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCancelPayment(sfWebRequest $request)
  {
    $packageTransaction = PackageTransactionQuery::create()
        ->filterByCollector($this->getCollector())
        ->filterById($request->getParameter('id'))
        ->filterByPaymentStatus(PackageTransactionPeer::STATUS_PENDING)
        ->findOne();

    if ($packageTransaction)
    {
      $packageTransaction->setPaymentStatus(PackageTransactionPeer::STATUS_CANCELED);
      $packageTransaction->save();

      $this->getUser()->setFlash('success', 'Order canceled successfully.');
    }
    else
    {
      $this->getUser()->setFlash('error', 'There is no active order');
    }

    $this->redirect('@mycq');
  }

  /**
   * Action CallbackIPN
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCallbackIPN(sfWebRequest $request)
  {
    if ('COMPLETED' != strtoupper($request->getParameter('payment_status')))
    {
      $this->getUser()->setFlash('success', 'The payment was not successful!');
      $this->redirect('@seller_become');
    }

    $this->forward404Unless($request->hasParameter('invoice'));

    $packageTransaction = PackageTransactionPeer::retrieveByPK($request->getParameter('invoice'));
    $this->forward404Unless((bool)$packageTransaction);

    $collector = $packageTransaction->getCollector();
    $package   = $packageTransaction->getPackage();

    $collector->setUserType(CollectorPeer::TYPE_SELLER);
    $collector->setItemsAllowed($package->getMaxItemsForSale());
    $collector->save();

    $packageTransaction->setPackagePrice($request->getParameter('mc_gross'));
    $packageTransaction->setPaymentStatus(PackageTransactionPeer::STATUS_PAID);
    $packageTransaction->save();

    // Send Mail To Seller
    $cqEmail = new cqEmail($this->getMailer());
    $sent    = $cqEmail->send('Seller/package_confirmation', array(
      'to'     => $collector->getEmail(),
      'subject'=> 'Thank you for becoming a seller',
      'params' => array(
        'collector'     => $collector,
        'package_name'  => $package->getPackageName(),
        'package_items' => $package->getMaxItemsForSale() < 0 ? 'Unlimited' : $package->getMaxItemsForSale(),
        'expiry_date'   => strtotime('+1 year'),
      ),
    ));

    // Deduct Number of time used promo code.
    if ($request->getParameter('option_name1', false) &&
        $promotion = PromotionPeer::retrieveByPK($request->getParameter('option_name1'))
    )
    {

      $promotion->setNoOfTimeUsed($promotion->getNoOfTimeUsed() - 1);
      $promotion->save();
    }

    $this->redirect('@mycq_collections');
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
