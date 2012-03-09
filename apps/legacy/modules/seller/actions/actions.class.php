<?php

/**
 * seller actions.
 *
 * @package    CollectorsQuest
 * @subpackage seller
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class sellerActions extends cqActions
{

  /**
   * Executes index action
   *
   * @return string
   */
  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executePackages(sfWebRequest $request)
  {
    $this->packagesForm = $packagesForm = new SellerPackagesForm(array(
      'payment_type'=> 'cc',
      'package_id'  => $request->getParameter('type')
    ));
    $this->freeSubscription = $freeSubscription = false;

    $pageTitle = ($request->getParameter('type') == 'upgrade') ? $this->__('Upgrade Your Package') : $this->__('Sell Your Collectibles!');
    $this->addBreadcrumb($pageTitle);
    $this->prependTitle($pageTitle);

    if ($request->isMethod('post'))
    {
      if ('applyPromo' == $request->getParameter('submit'))
      {
        $packagesForm->setPartialRequirements();
        $packagesForm->bind($request->getParameter($packagesForm->getName()));

        if ($packagesForm->isValid())
        {
          $promotion = $packagesForm->getPromotion();
          $package = $packagesForm->getPackage();
          $afterDiscountPrice = $package->getPackagePrice();

          if ($promotion)
          {
            if ('Fix' == $promotion->getAmountType())
            {
              $afterDiscountPrice = (float)$package->getPackagePrice() - (float)$promotion->getAmount();
              $discountTypeString = '$';
            }
            else
            {
              $discount = (float)($package->getPackagePrice() * $promotion->getAmount()) / 100;
              $afterDiscountPrice = (float)$package->getPackagePrice() - $discount;
              $discountTypeString = '%';
            }
            $freeSubscription = (bool)($afterDiscountPrice <= 0);

            if ($freeSubscription)
            {
              $this->discountMessage = 'Free Subscription!';
            }
            else
            {
              $this->discountMessage = sprintf('%d%s discount', $promotion->getAmount(), $discountTypeString);
            }
          }
        }
      }
      else
      {
        $packagesForm->bind($request->getParameter($packagesForm->getName()));

        if ($packagesForm->isValid())
        {
          //Process
          //Need refactoring
          $promotion = $packagesForm->getPromotion();
          $package = $packagesForm->getPackage();
          $collector = $this->getUser()->getCollector();
          $afterDiscountPrice = $package->getPackagePrice();

          // Save package information with payment status pending while payment successfully done.
          $packageInfo = array(
            'collector_id'       => $collector->getId(),
            'package_id'         => $package->getId(),
            'max_items_for_sale' => $package->getMaxItemsForSale(),
            'package_price'      => $package->getPackagePrice(),
            'payment_status'     => 'pending'
          );

          if ($promotion)
          {
            //Promo code used. Applying promotion
            //Promotion should be applied no matter when package will be paid.
            //TODO: Revert promo code use when cancel payment
            if ('Fix' == $promotion->getAmountType())
            {
              $afterDiscountPrice = (float)$package->getPackagePrice() - (float)$promotion->getAmount();
              $discountTypeString = '$';
            }
            else
            {
              $discount = (float)($package->getPackagePrice() * $promotion->getAmount()) / 100;
              $afterDiscountPrice = (float)$package->getPackagePrice() - $discount;
              $discountTypeString = '%';
            }
            $freeSubscription = (bool)($afterDiscountPrice <= 0);

            // Store Used Promotion Code Info by User.
            $promoTransactionInfo = array(
              'promotion_id' => $promotion->getId(),
              'collector_id' => $collector->getId(),
              'amount'       => $promotion->getAmount(),
              'amount_type'  => $promotion->getAmountType(),
            );
            $promoTransaction = PromotionTransaction::savePromotionTransaction($promoTransactionInfo);

            $packageInfo['package_price'] = $afterDiscountPrice;

            $promotion->setNoOfTimeUsed($promotion->getNoOfTimeUsed() - 1);
            $promotion->save();

            $replacements['%promo_offer%'] = "congratulation You get free subscription"; //Ugly
          }

          $packageTransaction = PackageTransaction::savePackageTransaction($packageInfo);

          if ($freeSubscription)
          {
            // Save package information with payment status paid while payment successfully done.
            $packageTransaction->setPackagePrice(0.0);
            $packageTransaction->setPaymentStatus(PackageTransactionPeer::STATUS_PAID);
            $packageTransaction->save();

            // Update collector become a seller
            $collector->setUserType(CollectorPeer::TYPE_SELLER);
            $collector->setItemsAllowed($package->getMaxItemsForSale());
            $collector->save();

            // Send Mail To Seller
            $to = $collector->getEmail();
            $subject = "Thank you for becoming a seller";
            $body = $this->getPartial(
              'emails/seller_package_confirmation', array(
                'collector'     => $collector,
                'package_name'  => $package->getPackageName(),
                'package_items' => ($package->getMaxItemsForSale() < 0) ? 'Unlimited' : $package->getMaxItemsForSale(),
              )
            );

            // Send off the email to the Seller
            $this->sendEmail($to, $subject, $body);

            $this->getUser()->setFlash('success', 'You received free subscription');
            $this->redirect('@manage_collections');
          }
          else if ('paypal' == $packagesForm->getValue('payment_type'))
          {
            $this->packageTransaction = $packageTransaction;
            $this->setTemplate('redirect');
            return sfView::SUCCESS;
          }
          else if ('cc' == $packagesForm->getValue('payment_type'))
          {
            $paypalAPI = new PayPal(array(
              'Sandbox'      => 'dev' == SF_ENV,
              'APIUsername'  => sfConfig::get('app_paypal_api_username'),
              'APIPassword'  => sfConfig::get('app_paypal_api_password'),
              'APISignature' => sfConfig::get('app_paypal_api_signature'),
            ));

            $directPaymentFields = array(
              'paymentaction'    => 'Sale', // How you want to obtain payment.  Authorization indidicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
              'ipaddress'        => $request->getRemoteAddress(),
              'returnfmfdetails' => '1', // Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
            );

            $creditCardDetails = array(
              'creditcardtype' => $packagesForm->getValue('card_type'),
              'acct'           => $packagesForm->getValue('cc_number'),
              'expdate'        => date('mY', strtotime($packagesForm->getValue('expiry_date'))),
              'cvv2'           => $packagesForm->getValue('cvv_number'),
            );

            $payerInfo = array(
              'email'       => $collector->getEmail(),
//              'payerid'     => '', // Unique PayPal customer ID for payer.
//              'payerstatus' => '', // Status of payer.  Values are verified or unverified
//              'business'    => '' // Payer's business name.
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
              'amt'          => $packageTransaction->getPackagePrice(), // Required.  Total amount of order, including shipping, handling, and tax.
              'currencycode' => sfConfig::get('app_paypal_currency_code', 'USD'), // Required.  Three-letter currency code.  Default is USD.
              'itemamt'      => $packageTransaction->getPackagePrice(), // Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
              'shippingamt'  => 0.00, // Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
              'handlingamt'  => 0.00, // Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
              'taxamt'       => 0.00, // Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax.
              'desc'         => sprintf('Package %s order', $package->getPackageName()), // Description of the order the customer is purchasing.  127 char max.
//              'custom'       => 'TEST', // Free-form field for your own use.  256 char max.
              'invnum'       => $packageTransaction->getId(), // Your own invoice or tracking number
//              'buttonsource' => '', // An ID code for use by 3rd party apps to identify transactions.
              'notifyurl'    => $this->generateUrl(sfConfig::get('app_paypal_notify_url'), array(), true) // URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
            );

            $orderItems = array(
              array(
                'l_name'                 => $package->getPackageName(), // Item Name.  127 char max.
//                'l_desc'                 => 'This is a test widget description.', // Item description.  127 char max.
                'l_amt'                  => $packageTransaction->getPackagePrice(), // Cost of individual item.
                'l_number'               => $package->getId(), // Item Number.  127 char max.
                'l_qty'                  => '1', // Item quantity.  Must be any positive integer.
                'l_taxamt'               => 0, // Item's sales tax amount.
              )
            );

            $paypalResult = $paypalAPI->DoDirectPayment(array(
              'DPFields'       => $directPaymentFields,
              'CCDetails'      => $creditCardDetails,
              'PayerName'      => $payerInfo,
              'BillingAddress' => $billingAddressInfo,
              'PaymentDetails' => $paymentDetails,
              'OrderItems'     => $orderItems,
            ));

            if ('SUCCESS' == strtoupper($paypalResult["ACK"]))
            {
              // Save package information with payment status paid while payment successfully done.
              $packageTransaction->setPaymentStatus(PackageTransactionPeer::STATUS_PAID);
              $packageTransaction->setPackagePrice($paypalResult['AMT']);
              $packageTransaction->save();

              $collector->setUserType(CollectorPeer::TYPE_SELLER);
              $collector->setItemsAllowed($package->getMaxItemsForSale());
              $collector->save();

              // Send Mail To Seller
              $to = $collector->getEmail();
              $subject = "Thank you for becoming a seller";
              $body = $this->getPartial(
                'emails/seller_package_confirmation', array(
                  'collector'     => $collector,
                  'package_name'  => $package->getPackageName(),
                  'package_items' => ($package->getMaxItemsForSale() < 0) ? 'Unlimited' : $package->getMaxItemsForSale(),
                )
              );

              // Send off the email to the Seller
              $result = $this->sendEmail($to, $subject, $body);

              $this->getUser()->setFlash('success', 'Payment received');
              $this->redirect('@manage_collections');
            }
            else
            {
              $this->sendEmail('developers@collectorsquest.com', 'CC DEBUG', var_export($paypalResult, true));

              $this->getUser()->setFlash('msg_payment', 'Your credit card information is invalid!');

              return sfView::SUCCESS;
            }
            // ----------------------- End PayPal Pro Code ---------------------------
          }
          else
          {
            //This should not happen cause if neither cc or paypal selected form is invalid
            //Need replace this with test
            throw new Exception(sprintf('Invalid payment type %s', $packagesForm->getValue('payment_type')));
          }
        }
      }
    }

    return sfView::SUCCESS;

    //OLD CODE BELOW

    $this->targetAction = ($request->getParameter('type') == 'upgrade') ? '@seller_upgrade_package' : '@seller_become';
    $this->bFreeSubscription = 0;

    $this->omPackages = PackagePeer::getAllPackages();

    $ssTitle = ($request->getParameter('type') == 'upgrade') ? $this->__('Upgrade Your Package') : $this->__('Sell Your Collectibles!');
    $this->addBreadcrumb($ssTitle);
    $this->prependTitle($ssTitle);

    if ($request->isMethod('post'))
    {
      if (!$request->hasParameter('package_id'))
      {
        $this->getUser()->setFlash('msg_package', 'No package selected!');
        return sfView::SUCCESS;
      }

      if (!$package = PackagePeer::retrieveByPK($request->getParameter('package_id')))
      {
        $this->getUser()->setFlash('msg_package', 'Invalid package');
        return sfView::SUCCESS;
      }

      $request->setParameter('package_price', $package->getPackagePrice());
      $request->setParameter('items_allowed', $package->getMaxItemsForSale());
      $request->setParameter('package_name', $package->getPackageName());

      if ($request->getParameter('commit') == "Apply")
      {
      }
      else
      {
        $bPromotionCode = false;
        $ssFreePackage = '';
        if ($request->getParameter('promo_code') != '')
        {
          $omPromotion = PromotionPeer::findByPromotionCode($request->getParameter('promo_code'));
          if ($omPromotion)
          {
            if ($omPromotion->getNoOfTimeUsed() == 0)
            {
              $this->getUser()->setFlash('msg_promotion_code', 'No of time Used of this promo code is over please use another promo code!');
              return sfView::SUCCESS;
            }
            else
            {
              if ($omPromotion->getExpiryDate('U') < time())
              {
                $this->getUser()->setFlash('msg_promotion_code', 'This Promotion code has been expired please use another promo code!');
                return sfView::SUCCESS;
              }
              else
              {
                if ($omPromotion->getAmountType() == 'Fix')
                {
                  $snAfterDiscountPrice = (float)$request->getParameter('package_price') - (float)$omPromotion->getAmount();
                }
                else
                {
                  $sfDiscount = (float)($request->getParameter('package_price') * $omPromotion->getAmount()) / 100;
                  $snAfterDiscountPrice = (float)$request->getParameter('package_price') - $sfDiscount;
                }
                $ssFreePackage = ($snAfterDiscountPrice <= 0) ? 'Free' : '';

                if ($ssFreePackage == 'Free')
                  $this->bFreeSubscription = 1;

                // Store Used Promotion Code Info by User.
                $amPromoTransactionInfo = array(
                  'promotion_id' => $omPromotion->getId(),
                  'collector_id' => $this->getUser()->getCollector()->getId(),
                  'amount'       => $omPromotion->getAmount(),
                  'amount_type'  => $omPromotion->getAmountType(),
                );
                $omPromotTransaction = PromotionTransaction::savePromotionTransaction($amPromoTransactionInfo);
                //End Store Data.
                $bPromotionCode = true;
              }
            }
          }
          else
          {
            $this->getUser()->setFlash('msg_promotion_code', 'Invalid promotion code!');
            return sfView::SUCCESS;
          }
        }
      }

      // Save package information with payment status paid while payment successfully done.
      $amPackageInfo = array(
        'collector_id'       => $this->getUser()->getCollector()->getId(),
        'package_id'         => $request->getParameter('package_id'),
        'max_items_for_sale' => $request->getParameter('items_allowed'),
        'package_price'      => $request->getParameter('package_price'),
        'payment_status'     => 'pending'
      );
      $omPackageTransaction = PackageTransaction::savePackageTransaction($amPackageInfo);
      // Update collector become a seller
      $amSellerInfo = array(
        'id'            => $this->getUser()->getCollector()->getId(),
        'user_type'     => 'Seller',
        'items_allowed' => 0
      );

      if ($ssFreePackage == "Free")
      {
        // Save package information with payment status paid while payment successfully done.
        $amPackageInfo = array(
          'id'             => $omPackageTransaction->getId(),
          'package_price'  => 0,
          'payment_status' => 'paid'
        );
        $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array(
          'id'            => $this->getUser()->getCollector()->getId(),
          'user_type'     => 'Seller',
          'items_allowed' => $request->getParameter('items_allowed')
        );
        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

        // Send Mail To Seller
        $to = $this->getCollector()->getEmail();
        $subject = "Thank you for becoming a seller";
        $body = $this->getPartial(
          'emails/seller_package_confirmation', array(
            'collector'     => $this->getCollector(),
            'package_name'  => $request->getParameter('package_name'),
            'package_items' => ($request->getParameter('items_allowed') < 0) ? 'Unlimited' : $request->getParameter('items_allowed')
          )
        );

        // If Promotion code is used and its valid then decreement by 1 of no_of_times_used promotion code.
        if ($bPromotionCode)
        {
          if ($omPromotTransaction)
          {
            Promotion::deductPromoCodeUsed($omPromotTransaction->getPromotionId());
            $replacements['%promo_offer%'] = "congratulation You get free subscription";
          }
        }

        // Send off the email to the Seller
        $this->sendEmail($to, $subject, $body);

        $this->getUser()->setFlash('success', 'You received free subscription');
        $this->redirect('@manage_collections');
      }
      else
      {
        // While Package is 0 then no need of send to request paypal account.
        // ------------ Start PayPal Code through CURL ------------------------
        $ssExpDateMonth = $request->getParameter('expiry_date_month');
        $ssPadDateMonth = str_pad($ssExpDateMonth, 2, '0', STR_PAD_LEFT);
        $ssExpDateYear = $request->getParameter('expiry_date_year');

        $amAPIData = array(
          'API_USERNAME'       => sfConfig::get('app_paypal_api_username'),
          'API_PASSWORD'       => sfConfig::get('app_paypal_api_password'),
          'API_SIGNATURE'      => sfConfig::get('app_paypal_api_signature'),
          'API_URL'            => sfConfig::get('app_paypal_api_url'),
          'API_CURRENCY_CODE'  => sfConfig::get('app_paypal_currency_code'),
          'API_PAYMENT_ACTION' => sfConfig::get('app_paypal_payment_action'),
          'PAYMENT_METHOD'     => sfConfig::get('app_paypal_payment_method'),
          'IS_ONLINE'          => sfConfig::get('app_paypal_is_online'),
          'AMOUNT'             => ($bPromotionCode == true) ? $snAfterDiscountPrice : $request->getParameter('package_price'),
          'CREDITCARDTYPE'     => $request->getParameter('card_type'),
          'ACCT'               => $request->getParameter('credit_card_number'),
          'EXPDATE'            => $ssPadDateMonth . $ssExpDateYear,
          'CVV2'               => $request->getParameter('cvv_number'),
        );

        //        list ($amAPIData['FIRSTNAME'], $amAPIData['LASTNAME']) = explode(' ', $this->getUser()->getCollector()->getDisplayName());
        //        $location = $this->getUser()->getCollector()->getLastCollectorGeocache();
        //
        $amAPIData['FIRSTNAME'] = $request->getParameter('first_name');
        $amAPIData['LASTNAME'] = $request->getParameter('last_name');
        $amAPIData['STREET'] = $request->getParameter('street');
        $amAPIData['CITY'] = $request->getParameter('city');
        $amAPIData['STATE'] = $request->getParameter('state');
        $amAPIData['COUNTRYCODE'] = $request->getParameter('country');
        $amAPIData['ZIP'] = $request->getParameter('zip');

        if ('dev' == SF_ENV)
        {
          //          echo "<pre>";
          //          print_r($amAPIData);
        }

        //        die();

        $ssNvpString = '&PAYMENTACTION=' . urlencode($amAPIData['API_PAYMENT_ACTION'])
            . '&AMT=' . urlencode($amAPIData['AMOUNT'])
            . '&CREDITCARDTYPE=' . urlencode($amAPIData['CREDITCARDTYPE'])
            . '&ACCT=' . urlencode($amAPIData['ACCT'])
            . '&EXPDATE=' . urlencode($amAPIData['EXPDATE'])
            . '&CVV2=' . urlencode($amAPIData['CVV2'])
            . '&CURRENCYCODE=' . urlencode($amAPIData['API_CURRENCY_CODE'])
            . '&FIRSTNAME=' . urlencode(@$amAPIData['FIRSTNAME'])
            . '&STREET=' . urlencode(@$amAPIData['STREET'])
            . '&CITY=' . urlencode(@$amAPIData['CITY'])
            . '&STATE=' . urlencode(@$amAPIData['STATE'])
            . '&COUNTRYCODE=' . urlencode(@$amAPIData['COUNTRYCODE'])
            . '&ZIP=' . urlencode(@$amAPIData['ZIP'])
            . '&IPADDRESS=' . urlencode($_SERVER['REMOTE_ADDR'])
            . '&ITEMAMT=' . urlencode($amAPIData['AMOUNT'])
            . '&SHIPPINGAMT=0'
            . '&TAXAMT=0';

        $omPayPalPro = new PayPal($amAPIData['API_USERNAME'], $amAPIData['API_PASSWORD'], $amAPIData['API_SIGNATURE'], $amAPIData['API_URL'], $amAPIData['IS_ONLINE']);
        $amResponse = $omPayPalPro->hash_call($amAPIData['PAYMENT_METHOD'], $ssNvpString);

        if ('dev' == SF_ENV)
        {
          //          echo "<pre>";
          //          print_r($amResponse);
          //
          //          if ('SUCCESS' == strtoupper($amResponse['ACK'])) {
          //            echo '<strong>Success</strong>';
          //          }
          //          die();
        }

        if (strtoupper($amResponse["ACK"]) == "SUCCESS")
        {
          // Save package information with payment status paid while payment successfully done.
          $amPackageInfo = array(
            'id'             => $omPackageTransaction->getId(),
            'package_price'  => $amResponse['AMT'],
            'payment_status' => 'paid'
          );
          $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
          // Update collector become a seller
          $amSellerInfo = array(
            'id'            => $this->getUser()->getCollector()->getId(),
            'user_type'     => 'Seller',
            'items_allowed' => $request->getParameter('items_allowed')
          );
          $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

          // Send Mail To Seller
          $to = $this->getCollector()->getEmail();
          $subject = "Thank you for becoming a seller";
          $body = $this->getPartial(
            'emails/seller_package_confirmation', array(
              'collector'     => $this->getCollector(),
              'package_name'  => $request->getParameter('package_name'),
              'package_items' => ($request->getParameter('items_allowed') < 0) ? 'Unlimited' : $request->getParameter('items_allowed')
            )
          );

          // If Promotion code is used and its valid then decreement by 1 of no_of_times_used promotion code.
          if ($bPromotionCode)
          {
            if ($omPromotTransaction)
            {
              $omPromotion = Promotion::deductPromoCodeUsed($omPromotTransaction->getPromotionId());
              if ($omPromotion)
                $ssDiscount = ($omPromotion->getAmountType() == "Percentage") ? $omPromotion->getAmount() . '%' : 'Fix $' . $omPromotion->getAmount();
            }
          }

          // Send off the email to the Seller
          $this->sendEmail($to, $subject, $body);

          //return $this->redirect('@collectible_sell?thankyou=true');
          $this->getUser()->setFlash('success', 'Payment received');
          $this->redirect('@manage_collections');
        }
        else
        {
          $this->sendEmail('developers@collectorsquest.com', 'CC DEBUG', var_export($amResponse, true));

          $this->getUser()->setFlash('msg_payment', 'Your credit card information is invalid!');
          return sfView::SUCCESS;
        }
        // ----------------------- End PayPal Code ---------------------------
      }
    }

    $countries = sfCultureInfo::getInstance('en')->getCountries();

    // Dirty hack but only solution currently
    $top = array(
      ''   => '',
      'US' => $countries['US'],
      'GB' => $countries['GB'],
      'AU' => $countries['AU'],
    );

    foreach ($top as $key => $value)
    {
      unset($countries[$key]);
    }

    $this->countries = array_merge($top, $countries);

    return sfView::SUCCESS;
  }

  public function executeAjaxSaveData(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      // Start Code for when user enter promotion code for get discount.
      if ($request->getParameter('promo_code'))
      {
        $omPromotion = PromotionPeer::findByPromotionCode($request->getParameter('promo_code'));
        if ($omPromotion)
        {
          if ($omPromotion->getNoOfTimeUsed() == 0)
          {
            echo 'error_No of time Used of this promo code is over please use another promo code!';
            exit;
          }
          else
          {
            if (strtotime($omPromotion->getExpiryDate()) < strtotime(date('Y-m-d')))
            {
              echo 'error_This Promotion code has been expired pleaes use another promo code!';
              exit;
            }
            else
            {
              if ($omPromotion->getAmountType() == 'Fix')
                $snAfterDiscountPrice = (float)$request->getParameter('package_price') - (float)$omPromotion->getAmount();
              else
              {
                $sfDiscount = (float)($request->getParameter('package_price') * $omPromotion->getAmount()) / 100;
                $snAfterDiscountPrice = (float)$request->getParameter('package_price') - $sfDiscount;
              }
              // Store Used Promotion Code Info by User.
              $amPromoTransactionInfo = array(
                'promotion_id' => $omPromotion->getId(),
                'collector_id' => $this->getUser()->getCollector()->getId(),
                'amount'       => $omPromotion->getAmount(),
                'amount_type'  => $omPromotion->getAmountType(),
              );
              $omPromotTransaction = PromotionTransaction::savePromotionTransaction($amPromoTransactionInfo);
              //End Store Data.
              // Save package information with payment status paid while payment successfully done.
              $amPackageInfo = array(
                'collector_id'       => $this->getUser()->getCollector()->getId(),
                'package_id'         => $request->getParameter('package_id'),
                'max_items_for_sale' => $request->getParameter('items_allowed'),
                'package_price'      => $request->getParameter('package_price'),
                'payment_status'     => 'pending'
              );
              $omPackageTransaction = PackageTransaction::savePackageTransaction($amPackageInfo);
              // Update collector become a seller
              $amSellerInfo = array(
                'id'            => $this->getUser()->getCollector()->getId(),
                'user_type'     => 'Seller',
                'items_allowed' => 0
              );
              CollectorPeer::updateCollectorAsSeller($amSellerInfo);

              // End Save Package Information
              // Return After Discount Price and PackageTransactio Id.
              echo 'success_' . $snAfterDiscountPrice . '_' . $omPackageTransaction->getId() . '_' . $omPromotTransaction->getPromotionId();
              exit;
            }
          }
        }
        else
        {
          echo 'error_Invalid promotion code!';
          exit;
        }
      }
      // End code
      else
      {
        // Save package information with payment status paid while payment successfully done.
        $amPackageInfo = array(
          'collector_id'       => $this->getUser()->getCollector()->getId(),
          'package_id'         => $request->getParameter('package_id'),
          'max_items_for_sale' => $request->getParameter('items_allowed'),
          'package_price'      => $request->getParameter('package_price'),
          'payment_status'     => 'pending'
        );
        $omPackageTransaction = PackageTransaction::savePackageTransaction($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array(
          'id'            => $this->getUser()->getCollector()->getId(),
          'user_type'     => 'Seller',
          'items_allowed' => 0
        );
        //        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);
        echo $omPackageTransaction->getId();
        exit;
      }
    }

    return sfView::NONE;
  }

  /**
   * @param sfWebRequest $request
   * @return void
   */
  public function executeUpdateOrder(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      if ($request->getParameter('payment_status') == "Completed")
      {
        // Save package information with payment status paid while payment successfully done.
        $amPackageInfo = array(
          'id'             => $request->getParameter('invoice'),
          'package_price'  => $request->getParameter('mc_gross'),
          'payment_status' => 'paid'
        );
        $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array(
          'id'            => $this->getUser()->getCollector()->getId(),
          'user_type'     => 'Seller',
          'items_allowed' => $request->getParameter('custom')
        );
        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

        // Send Mail To Seller
        $to = $this->getCollector()->getEmail();
        $subject = "Thank you for becoming a seller";
        $body = $this->getPartial(
          'emails/seller_package_confirmation', array(
            'collector'     => $this->getCollector(),
            'package_name'  => $request->getParameter('package_name'),
            'package_items' => ($request->getParameter('items_allowed') <= 0) ? 'Unlimited' : $request->getParameter('items_allowed')
          )
        );

        // Deduct Number of time used promo code.
        if ($request->getParameter('option_name1') && $request->getParameter('option_name1') > 0)
        {
          $omPromotion = Promotion::deductPromoCodeUsed($request->getParameter('option_name1'));
          if ($omPromotion)
            $ssDiscount = ($omPromotion->getAmountType() == "Percentage") ? $omPromotion->getAmount() . '%' : 'Fix $' . $omPromotion->getAmount();
        }

        // Send off the email to the Seller
        $this->sendEmail($to, $subject, $body);

        $this->redirect('@manage_collections');
      }
      else
      {
        $this->getUser()->setFlash('msg_package', 'Your Payment not successfully done!');
        $this->redirect('@become_seller?id=' . $this->getUser()->getCollector()->getId());
      }
    }
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeCallbackIPN(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      if ($request->getParameter('payment_status') == "Completed")
      {
        // Save package information with payment status paid while payment successfully done.
        $amPackageInfo = array(
          'id'             => $request->getParameter('invoice'),
          'package_price'  => $request->getParameter('mc_gross'),
          'payment_status' => 'paid'
        );
        $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array(
          'id'            => $this->getUser()->getCollector()->getId(),
          'user_type'     => 'Seller',
          'items_allowed' => $request->getParameter('custom')
        );
        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

        // Send Mail To Seller
        $to = $this->getCollector()->getEmail();
        $subject = "Thank you for becoming a seller";
        $body = $this->getPartial(
          'emails/seller_package_confirmation', array(
            'collector'     => $this->getCollector(),
            'package_name'  => $request->getParameter('package_name'),
            'package_items' => ($request->getParameter('items_allowed') <= 0) ? 'Unlimited' : $request->getParameter('items_allowed')
          )
        );

        // Deduct Number of time used promo code.
        if ($request->getParameter('option_name1') && $request->getParameter('option_name1') > 0)
        {
          $omPromotion = Promotion::deductPromoCodeUsed($request->getParameter('option_name1'));
          if ($omPromotion)
            $ssDiscount = ($omPromotion->getAmountType() == "Percentage") ? $omPromotion->getAmount() . '%' : 'Fix $' . $omPromotion->getAmount();
        }

        // Send off the email to the Seller
        $this->sendEmail($to, $subject, $body);

        $this->redirect('@manage_collections');
      }
      else
      {
        $this->getUser()->setFlash('msg_package', 'Your Payment not successfully done!');
        $this->redirect('@seller_become?id=' . $this->getUser()->getCollector()->getId());
      }
    }

    return sfView::NONE;
  }

  /**
   * Action CancelPayment
   *
   * @param sfWebRequest $request
   *
   */
  public function executeCancelPayment(sfWebRequest $request)
  {
    //    var_dump($request->getParameterHolder()->getAll());
    //    die();
  }

  /**
   * Action Redirect
   *
   * @param sfWebRequest $request
   *
   */
  public function executeRedirect(sfWebRequest $request)
  {
    dd($request, $this->packageTransaction);
  }

}
