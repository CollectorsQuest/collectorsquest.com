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
    if (!$this->getUser()->isAuthenticated())
    {
      $this->redirect('@login');
    }

    $this->ssAction = ($request->getParameter('type') == 'upgrade') ? '@seller_upgrade_package?id=' : '@seller_become?id=';
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
        // ------------ Start Code for promotion code ------------------------
        $bPromotionCode = false;
        $ssFreePackage = '';
        if ($request->getParameter('promo_code') != '')
        {
          $omPromotion = Promotion::checkPromotionCode($request->getParameter('promo_code'));
          if ($omPromotion)
          {
            if ($omPromotion->getNoOfTimeUsed() == 0)
            {
              $this->getUser()->setFlash('msg_promotion_code', 'Allowed number of uses for this promo code was reached!');
              return sfView::SUCCESS;
            }
            else
            {
              if (strtotime($omPromotion->getExpiryDate()) < strtotime(date('Y-m-d')))
              {
                $this->getUser()->setFlash('msg_promotion_code', 'This promotion code has expired!');
                return sfView::SUCCESS;
              }
              else
              {
                if ($omPromotion->getAmountType() == 'Fix')
                {
                  $snAfterDiscountPrice = (float) $request->getParameter('package_price') - (float) $omPromotion->getAmount();
                  $snDiscountTypeString = '$';
                }
                else
                {
                  $sfDiscount = (float) ($request->getParameter('package_price') * $omPromotion->getAmount()) / 100;
                  $snAfterDiscountPrice = (float) $request->getParameter('package_price') - $sfDiscount;
                  $snDiscountTypeString = '%';
                }
                $ssFreePackage = ($snAfterDiscountPrice <= 0) ? 'Free' : '';

                if ($ssFreePackage == 'Free')
                {
                  $this->getUser()->setFlash('msg_promotion_code', 'Free Subscription!');
                  $this->bFreeSubscription = true;
                }
                else
                {
                  $this->getUser()->setFlash('msg_promotion_code', sprintf('%d%s discount', $omPromotion->getAmount(), $snDiscountTypeString));
                  $this->bFreeSubscription = false;
                }
                $bPromotionCode = true;
              }
            }
          }
          else
          {
            $this->getUser()->setFlash('msg_promotion_code', 'InValid!');
          }
        }
        return sfView::SUCCESS;
        //--------------------- End promotion code ---------------------------
      }
      else
      {
        $bPromotionCode = false;
        $ssFreePackage = '';
        if ($request->getParameter('promo_code') != '')
        {
          $omPromotion = Promotion::checkPromotionCode($request->getParameter('promo_code'));
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
                $this->getUser()->setFlash('msg_promotion_code', 'This Promotion code has been expired pleaes use another promo code!');
                return sfView::SUCCESS;
              }
              else
              {
                if ($omPromotion->getAmountType() == 'Fix')
                {
                  $snAfterDiscountPrice = (float) $request->getParameter('package_price') - (float) $omPromotion->getAmount();
                }
                else
                {
                  $sfDiscount = (float) ($request->getParameter('package_price') * $omPromotion->getAmount()) / 100;
                  $snAfterDiscountPrice = (float) $request->getParameter('package_price') - $sfDiscount;
                }
                $ssFreePackage = ($snAfterDiscountPrice <= 0) ? 'Free' : '';

                if ($ssFreePackage == 'Free')
                  $this->bFreeSubscription = 1;

                // Store Used Promotion Code Info by User.
                $amPromoTransactionInfo = array(
                  'promotion_id' => $omPromotion->getId(),
                  'collector_id' => $this->getUser()->getCollector()->getId(),
                  'amount' => $omPromotion->getAmount(),
                  'amount_type' => $omPromotion->getAmountType(),
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
      $amPackageInfo = array('collector_id' => $this->getUser()->getCollector()->getId(),
        'package_id' => $request->getParameter('package_id'),
        'max_items_for_sale' => $request->getParameter('items_allowed'),
        'package_price' => $request->getParameter('package_price'),
        'payment_status' => 'pending'
      );
      $omPackageTransaction = PackageTransaction::savePackageTransaction($amPackageInfo);
      // Update collector become a seller
      $amSellerInfo = array('id' => $this->getUser()->getCollector()->getId(),
        'user_type' => 'Seller',
        'items_allowed' => 0
      );
//      $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

      if ($ssFreePackage == "Free")
      {
        // Save package information with payment status paid while payment successfully done.
        $amPackageInfo = array(
          'id' => $omPackageTransaction->getId(),
          'package_price' => 0,
          'payment_status' => 'paid'
        );
        $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array('id' => $this->getUser()->getCollector()->getId(),
          'user_type' => 'Seller',
          'items_allowed' => $request->getParameter('items_allowed')
        );
        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

        // Send Mail To Seller
        $to = $this->getCollector()->getEmail();
        $subject = "Thank you for becoming a seller";
        $body = $this->getPartial(
          'emails/seller_package_confirmation', array(
            'collector' => $this->getCollector(),
            'package_name' => $request->getParameter('package_name'),
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
      { // While Package is 0 then no need of send to request paypal account.
        // ------------ Start PayPal Code through CURL ------------------------
        $ssExpDateMonth = $request->getParameter('expiry_date_month');
        $ssPadDateMonth = str_pad($ssExpDateMonth, 2, '0', STR_PAD_LEFT);
        $ssExpDateYear = $request->getParameter('expiry_date_year');

        $amAPIData = array(
          'API_USERNAME' => sfConfig::get('app_paypal_api_username'),
          'API_PASSWORD' => sfConfig::get('app_paypal_api_password'),
          'API_SIGNATURE' => sfConfig::get('app_paypal_api_signature'),
          'API_URL' => sfConfig::get('app_paypal_api_url'),
          'API_CURRENCY_CODE' => sfConfig::get('app_paypal_currency_code'),
          'API_PAYMENT_ACTION' => sfConfig::get('app_paypal_payment_action'),
          'PAYMENT_METHOD' => sfConfig::get('app_paypal_payment_method'),
          'IS_ONLINE' => sfConfig::get('app_paypal_is_online'),
          'AMOUNT' => ($bPromotionCode == true) ? $snAfterDiscountPrice : $request->getParameter('package_price'),
          'CREDITCARDTYPE' => $request->getParameter('card_type'),
          'ACCT' => $request->getParameter('credit_card_number'),
          'EXPDATE' => $ssPadDateMonth . $ssExpDateYear,
          'CVV2' => $request->getParameter('cvv_number'),
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
          . '&TAXAMT=0'
        ;

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
          $amPackageInfo = array('id' => $omPackageTransaction->getId(),
            'package_price' => $amResponse['AMT'],
            'payment_status' => 'paid'
          );
          $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
          // Update collector become a seller
          $amSellerInfo = array(
            'id' => $this->getUser()->getCollector()->getId(),
            'user_type' => 'Seller',
            'items_allowed' => $request->getParameter('items_allowed')
          );
          $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

          // Send Mail To Seller
          $to = $this->getCollector()->getEmail();
          $subject = "Thank you for becoming a seller";
          $body = $this->getPartial(
              'emails/seller_package_confirmation', array(
              'collector' => $this->getCollector(),
              'package_name' => $request->getParameter('package_name'),
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
          //$this->redirect($this->ssAction.$this->getUser()->getCollector()->getId());
        }
        // ----------------------- End PayPal Code ---------------------------
      }
    }

    $countries = sfCultureInfo::getInstance('en')->getCountries();

    // Dirty hack but only solution currently
    $top = array(
      '' => '',
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
        $omPromotion = Promotion::checkPromotionCode($request->getParameter('promo_code'));
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
                $snAfterDiscountPrice = (float) $request->getParameter('package_price') - (float) $omPromotion->getAmount();
              else
              {
                $sfDiscount = (float) ($request->getParameter('package_price') * $omPromotion->getAmount()) / 100;
                $snAfterDiscountPrice = (float) $request->getParameter('package_price') - $sfDiscount;
              }
              // Store Used Promotion Code Info by User.
              $amPromoTransactionInfo = array('promotion_id' => $omPromotion->getId(),
                'collector_id' => $this->getUser()->getCollector()->getId(),
                'amount' => $omPromotion->getAmount(),
                'amount_type' => $omPromotion->getAmountType(),
              );
              $omPromotTransaction = PromotionTransaction::savePromotionTransaction($amPromoTransactionInfo);
              //End Store Data.
              // Save package information with payment status paid while payment successfully done.
              $amPackageInfo = array('collector_id' => $this->getUser()->getCollector()->getId(),
                'package_id' => $request->getParameter('package_id'),
                'max_items_for_sale' => $request->getParameter('items_allowed'),
                'package_price' => $request->getParameter('package_price'),
                'payment_status' => 'pending'
              );
              $omPackageTransaction = PackageTransaction::savePackageTransaction($amPackageInfo);
              // Update collector become a seller
              $amSellerInfo = array('id' => $this->getUser()->getCollector()->getId(),
                'user_type' => 'Seller',
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
      }// End code
      else
      {
        // Save package information with payment status paid while payment successfully done.
        $amPackageInfo = array('collector_id' => $this->getUser()->getCollector()->getId(),
          'package_id' => $request->getParameter('package_id'),
          'max_items_for_sale' => $request->getParameter('items_allowed'),
          'package_price' => $request->getParameter('package_price'),
          'payment_status' => 'pending'
        );
        $omPackageTransaction = PackageTransaction::savePackageTransaction($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array('id' => $this->getUser()->getCollector()->getId(),
          'user_type' => 'Seller',
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
        $amPackageInfo = array('id' => $request->getParameter('invoice'),
          'package_price' => $request->getParameter('mc_gross'),
          'payment_status' => 'paid'
        );
        $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array('id' => $this->getUser()->getCollector()->getId(),
          'user_type' => 'Seller',
          'items_allowed' => $request->getParameter('custom')
        );
        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

        // Send Mail To Seller
        $to = $this->getCollector()->getEmail();
        $subject = "Thank you for becoming a seller";
        $body = $this->getPartial(
            'emails/seller_package_confirmation', array(
            'collector' => $this->getCollector(),
            'package_name' => $request->getParameter('package_name'),
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
        $amPackageInfo = array('id' => $request->getParameter('invoice'),
          'package_price' => $request->getParameter('mc_gross'),
          'payment_status' => 'paid'
        );
        $omPackageTransaction = PackageTransaction::updatePaymentStatus($amPackageInfo);
        // Update collector become a seller
        $amSellerInfo = array('id' => $this->getUser()->getCollector()->getId(),
          'user_type' => 'Seller',
          'items_allowed' => $request->getParameter('custom')
        );
        $omSeller = CollectorPeer::updateCollectorAsSeller($amSellerInfo);

        // Send Mail To Seller
        $to = $this->getCollector()->getEmail();
        $subject = "Thank you for becoming a seller";
        $body = $this->getPartial(
            'emails/seller_package_confirmation', array(
            'collector' => $this->getCollector(),
            'package_name' => $request->getParameter('package_name'),
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

}
