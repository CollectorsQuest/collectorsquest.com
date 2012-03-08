<?php
/* @var $packagesForm SellerPackagesForm */
/* @var $freeSubscription bool */
/* @var $sf_request sfWebRequest */
/* @var $sf_user cqUser */

?>
<br clear="all" />
<?php if ($sf_request->getParameter('msg')): ?>
<ul class="error_list">
  <li>
    Your plan has been expired
  </li>
</ul>
<?php endif; ?>

<?php $packagesForm->renderGlobalErrors(); ?>

<table width="902px" border="0" cellspacing="0" cellpadding="0" class="formoffer">
  <tr>
    <td width="50%" valign="top">
      <table width="90%" border="0" cellspacing="0" cellpadding="0" class="offerleft">
        <tr>
          <td valign="top"><h6>Heavy Traffic</h6>

            <div class="space_text">500,000+ collectors and growing with more monthly traffic than flea markets and floor shows.</div>
            <h6>Broader Exposure</h6>

            <div class="space_text">
              Have your items for sale matched against related content on the site.
              <a href="#" onClick="window.open('/images/example.jpg','','width=762,height=524'); return false;">See example</a>.
            </div>
            <h6>Flat Rate with No Transaction Fees</h6>

            <div class="space_text">No fancy math needed. It is what it is. Annual subscribers can sell and replace as many items as you want each month at no additional cost.</div>
            <h6>Annual Expiration Dates and Payment Choice</h6>

            <div class="space_text">You can continue listing your items for sale for up to one year. Payment method for your customers is YOUR decision.</div>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </td>
    <td width="50%" valign="top">
      <form action="" name="frmpackage" id="frmpackage" method="post">
        <?php
        $packagesForm->renderHiddenFields();
// Seller Details
//        echo input_hidden_tag('user_type', 'Seller');
//        echo input_hidden_tag('items_allowed');
//        echo input_hidden_tag('package_price');
//        echo input_hidden_tag('package_name');
//        echo input_hidden_tag('free_subscription', $freeSubscription);
//        echo input_hidden_tag('commit');
//        echo input_hidden_tag('type', $sf_request->getParameter('type'));
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="offerright">
          <tr>
            <td>
              <fieldset>
                <legend>Choose a Plan</legend>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <?php echo $packagesForm['package_id']->renderError(); ?>
                  <?php echo $packagesForm['package_id']->render(); ?>
                  <?php /*if ($sf_user->hasFlash('msg_package')): ?>
                  <tr>
                    <td colspan="2">
                      <ul class="error_list"><li><?php echo $sf_user->getFlash('msg_package') ?></li></ul>
                    </td>
                  </tr>
                <?php endif; ?>
                <tr>
                  <td colspan="2"><h5>Casual Plan - Choose One</h5></td>
                </tr>
                <?php
                if ($omPackages):
                  $bFlag = 1;
                  foreach ($omPackages as $asValues):
                    if ($asValues['PLAN_TYPE'] == "Casual"):
                      ?>
                      <tr>
                        <td colspan="2">
                          <table width="100%" cellpadding="5" cellspacing="5">
                            <tr>
                              <td>
                                <?php
                                echo radiobutton_tag(
                                  'package_id', $asValues['ID'], $sf_request->getParameter('package_id') == $asValues['ID'], array('id' => 'package_id_' . $asValues['ID'], 'onclick' => 'setPackageInformation(' . $asValues['MAX_ITEMS_FOR_SALE'] . ', this.value,' . $asValues['PACKAGE_PRICE'] . ',"' . $asValues['PACKAGE_NAME'] . '");'));
                                ?>
                              </td>
                              <td width="5px"></td>
                              <td>
                                <?php echo money_format('%.2n', $asValues['PACKAGE_PRICE']); ?> -
                                <?php echo $asValues['PACKAGE_NAME']; ?>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    <?php else:
                      if ($bFlag == 1): ?>
                        <tr>
                          <td colspan="2"><br /><h5>Power Plan - Annual Fee</h5></td>
                        </tr>
                        <?php $bFlag = 0;
                      endif; ?>
                      <tr>
                        <td colspan="2">
                          <table width="100%" cellpadding="5" cellspacing="5">
                            <tr>
                              <td>
                                <?php
                                echo radiobutton_tag(
                                  'package_id', $asValues['ID'], $sf_request->getParameter('package_id') == $asValues['ID'], array('id' => 'package_id_' . $asValues['ID'], 'onclick' => 'setPackageInformation(' . $asValues['MAX_ITEMS_FOR_SALE'] . ', this.value, ' . $asValues['PACKAGE_PRICE'] . ',"' . $asValues['PACKAGE_NAME'] . '");'
                                ));
                                ?>
                              </td>
                              <td width="5px"></td>
                              <td>
                                <?php echo money_format('%.2n', $asValues['PACKAGE_PRICE']); ?> -
                                <?php echo $asValues['PACKAGE_NAME']; ?>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    <?php
                    endif;
                  endforeach;
                endif; */
                  ?>
                  <tr>
                    <td><h5 class="title_h5">Promotion Code: </h5></td>
                    <td>
                      <?php if ($packagesForm['promo_code']->hasError()): ?>
                        <?php echo $packagesForm['promo_code']->renderError(); ?><br />
                      <?php endif; ?>
                      <?php echo $packagesForm['promo_code']->render(); ?>
                      <button type="submit" name="submit" value="applyPromo" class="submit"
                              style="cursor: pointer; border: 1px solid; float: none; display: inline-block;" title="Check promo code">Apply
                      </button>
                    </td>
                  </tr>
                </table>
                <?php if (!$freeSubscription): ?>
                <?php /* @var $paymentType sfFormField */ ?>
                <?php echo $packagesForm['payment_type']->render(array('style'=> 'height: 24px;')); ?>
                <?php /* ?>
  <tr>
    <td colspan="2">
      <input type="radio" name="payment_type" id="payment_paypal" value="paypal" />
      <label for="payment_paypal">
        <?php echo image_tag('legacy/payment/paypal.gif', array('style' => 'height: 24px;')) ?>
      </label>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="vertical-align: middle!important;">
      <input type="radio" name="payment_type" id="payment_cc" value="package" checked="checked" />
      <label for="payment_cc">
        <?php echo image_tag('legacy/payment/cc.gif', array('style' => 'height: 24px;')) ?>
      </label>
    </td>
  </tr>
 <?php */
                ?>
                <table id="fieldset_cc">
                  <?php echo $packagesForm['card_type']->renderRow() ?>
                  <?php echo $packagesForm['cc_number']->renderRow() ?>
                  <?php echo $packagesForm['expiry_date']->renderRow() ?>
                  <?php echo $packagesForm['cvv_number']->renderRow(); ?>
                  <?php $ccFields = array(
                  'first_name', 'last_name', 'street', 'city', 'state', 'zip', 'country',
                ); ?>
                  <?php foreach ($ccFields as $field): ?>
                  <?php echo $packagesForm[$field]->renderRow(); ?>
                  <?php endforeach; ?>
                  <tr>
                    <td colspan="2">
                      <?php if ($packagesForm['term_condition']->hasError()): ?>
                      <?php echo $packagesForm['term_condition']->renderError() ?>
                      <?php endif; ?>
                      <?php echo $packagesForm['term_condition']->render() ?>&nbsp;<label for="<?php echo $packagesForm['term_condition']->renderId() ?>">I accept the
                      <a href="/blog/terms-and-conditions/" title="terms and conditions" target="_blank">terms and conditions</a> set forth by this site.</label>
                    </td>
                  </tr>
                  <?php /*
  <tr>
    <td>
      <div class="text_valign">
        Card Type:
      </div>
    </td>
    <td>
      <?php
      $amCardType = array(
        'Visa'             => 'Visa',
        'MasterCard'       => 'MasterCard',
        'Discover'         => 'Discover',
        'American Express' => 'American Express'
      );
      echo select_tag('card_type', options_for_select($amCardType, $sf_request->getParameter('card_type')), array('style' => "width:150px;"));
      ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">Credit Card Number:</div>
    </td>
    <td>
      <?php echo input_tag('credit_card_number', $sf_request->getParameter('credit_card_number'), array('style' => "width:142px;")); ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valign">Expiration Date:</div>
    </td>
    <td>
      <?php
      $anMonths = $anYears = array();
      for ($snI = 1; $snI <= 12; $snI++)
        $anMonths[$snI] = $snI;
      for ($snI = date('Y'); $snI < date('Y') + 10; $snI++)
        $anYears[$snI] = $snI;

      echo select_tag('expiry_date_month', options_for_select($anMonths, $sf_request->getParameter('expiry_date_month')), array('style' => 'width:50px;')) . '&nbsp;&nbsp;';
      echo select_tag('expiry_date_year', options_for_select($anYears, $sf_request->getParameter('expiry_date_year')), array('style' => 'width:92px;'));
      ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">Card Verification Number:</div>
    </td>
    <td>
      <?php echo input_tag('cvv_number', $sf_request->getParameter('cvv_number'), array(
      'maxlength' => 3,
      'style'     => "width:142px;"
    )); ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">First name:</div>
    </td>
    <td>
      <?php echo input_tag('first_name', $sf_request->getParameter('first_name'), array('style' => "width:142px;")) ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">Last name:</div>
    </td>
    <td>
      <?php echo input_tag('last_name', $sf_request->getParameter('last_name'), array('style' => "width:142px;")) ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">Street:</div>
    </td>
    <td>
      <?php echo input_tag('street', $sf_request->getParameter('street'), array('style' => "width:142px;")) ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">City:</div>
    </td>
    <td>
      <?php echo input_tag('city', $sf_request->getParameter('city'), array('style' => "width:142px;")) ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">State:</div>
    </td>
    <td>
      <?php echo input_tag('state', $sf_request->getParameter('state'), array('style' => "width:142px;")) ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">Zip:</div>
    </td>
    <td>
      <?php echo input_tag('zip', $sf_request->getParameter('zip'), array('style' => "width:142px;")) ?>
    </td>
  </tr>
 */ ?>
                  <?php /*
  <tr>
    <td>
      <div class="text_valignnew">Country:</div>
    </td>
    <td>
      <?php
      echo $packagesForm['country']->render(array('style'=> 'width: 142px;'));
      //                        $country = new sfWidgetFormI18nChoiceCountry(array('choices' => $countries, 'add_empty' => true), array('style' => 'width: 142px;')) ;
      //                        $country->setDefault($sf_request->getParameter('country'));
      //                        echo $country->render('country');
      ?>
    </td>
  </tr>
 */ ?>
                </table>
                <?php if ($sf_user->hasFlash('msg_payment')): ?>
                  <table>
                    <tr>
                      <td colspan="2">
                        <ul class="error_list">
                          <li><?php echo $sf_user->getFlash('msg_payment'); ?></li>
                        </ul>
                      </td>
                    </tr>
                  </table>
                  <?php endif; ?>
                <?php /*
                    <tr>
                    <td>
                    <br /><h5>OR Pay Via PayPal</h5>
                    </td>
                    </tr>
                    <tr>
                    <td>
                    <div style="float: left;"><?php echo image_tag('legacy/btn_xpressCheckout.gif', array('onclick' => "getpayment('paypal');return false;", 'style' => 'cursor:pointer;float:right;')); ?></div>
                    </td>
                    </tr>
                   */
                ?>
                <?php endif; ?>
                <h5 style="margin-top: 10px;">* To avoid interruption of service, annual subscriptions
                  automatically renew at the end of the subscription period</h5>
              </fieldset>
              <button type="submit" class="submit" style="margin-left: 190px;">
                <span><span>Sign up</span></span>
              </button>
            </td>
          </tr>
          <tr>
            <td>
              <?php /*if ($freeSubscription): ?>
    <button type="submit" value="Sign up" class="submit" style="margin-left:190px;" onclick="freeSubscription('package');return false;">
      <span><span>Sign up</span></span>
    </button>
    <?php else: ?>
    <button type="submit" value="Sign up" class="submit" style="margin-left:190px;" onclick="getpayment($('input[name*=payment_type]:checked').val()); return false;">
      <span><span>Sign up</span></span>
    </button>
    <?php endif; ?>
    <noscript style="text-align:center;">
      <span style="color:#FF0000; font-weight:bold; text-align:center;">Your browser detect disable javascript! Please enable it!</span>&nbsp;
    </noscript>
    */ ?>
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
<form action="<?php echo sfConfig::get("app_paypal_url"); ?>" name="frmpaypal" id="frmpaypal" method="post">
  <?php
  // Paypal Details
  echo input_hidden_tag('item_number');
  echo input_hidden_tag('item_name');
  echo input_hidden_tag('custom');
  echo input_hidden_tag('amount');
  echo input_hidden_tag('invoice');
  echo input_hidden_tag('on0');
  echo input_hidden_tag('cmd', '_xclick');
  echo input_hidden_tag('shipping', 0);
  echo input_hidden_tag('shipping2', 0);
  echo input_hidden_tag('no_shipping', 1);
  echo input_hidden_tag('tax', 0);
  echo input_hidden_tag('no_note', '1');
  echo input_hidden_tag('rm', 2);
  echo input_hidden_tag('business', sfConfig::get("app_paypal_merchant_account"));
  echo input_hidden_tag('currency_code', sfConfig::get("app_paypal_currency"));
  echo input_hidden_tag('currency', sfConfig::get("app_paypal_currency"));
  echo input_hidden_tag('return', url_for1(sfConfig::get("app_paypal_return_url"), true));
  echo input_hidden_tag('cancel_return', url_for1(sfConfig::get("app_paypal_cancel_url"), true));
  echo input_hidden_tag('notify_url', url_for1(sfConfig::get("app_paypal_notify_url"), true));
  ?>
</form>
<div class="clearfix append-bottom">&nbsp;</div>
<div id="no_update"></div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  <?php /*
  function setPackageInformation(snItemAllowed, snPackageId, snPackagePrice, ssPackageName) {
    $('#items_allowed').val(snItemAllowed);
    //    $('#package_id').val(snPackageId);
    $('#package_price').val(snPackagePrice);
    $('#package_name').val(ssPackageName);

    // For PayPal
    $('#item_number').val(snPackageId);
    $('#item_name').val(ssPackageName);
    $('#amount').val(snPackagePrice);
    $('#custom').val(snItemAllowed);
  }

  function freeSubscription(frm) {
    var numberOfItems = $("#items_allowed").val();
    var bTermCondition = true;

    console.log(numberOfItems);

    if (!numberOfItems) {
      alert('Please Choose a Plan');
      return false;
    }
    else {
      if ($('#free_subscription').val() == 1) {
        if (!$('#term_condition').is(':checked')) {
          alert('Please accept the Terms and Conditions');
          bTermCondition = false;
        }
      }
      if (bTermCondition) {
        var ssCommit = ($('#free_subscription').val() == 1) ? '' : 'Apply';
        $('#commit').val(ssCommit);
        $('#frm' + frm).submit();
      }
    }
    return bTermCondition;
  }

  function getpayment(frm) {
    var numberOfItems = $("#items_allowed").val();
    var bTermCondition = true;

    if (frm == 'paypal') {
      if (numberOfItems > 0) {
        if ($('#term_condition').is(':checked')) {
          ssPromoCode = jQuery.trim($('#promo_code').val());
          if (ssPromoCode != '') {
            $.ajax({
              update:"no_update",
              type:"POST",
              url:"<?php echo url_for('@seller_ajax_save'); ?>",
              data:"package_id=" + $('input[name*="package_id"]:checked').val() + "&items_allowed=" + $('#items_allowed').val() + "&package_price=" + $('#package_price').val() + "&promo_code=" + ssPromoCode,
              success:function (ssResponse) {
                var amResponse = ssResponse.split('_');
                if (amResponse[0] == 'success') {
                  //if(isFloat(amResponse[1]))
                  //{
                  $('#amount').val(amResponse[1]);
                  $('#invoice').val(amResponse[2]);
                  $('#on0').val(amResponse[3]);
                  $('#frm' + frm).submit();
                  //}
                }
                else {
                  alert(amResponse[1]);
                  return false;
                }
              }
            });
          }
          else {
            $.ajax({
              update:"no_update",
              type:"POST",
              url:"<?php echo url_for('@seller_ajax_save'); ?>",
              data:"package_id=" + $('input[name*="package_id"]:checked').val() + "&items_allowed=" + $('#items_allowed').val() + "&package_price=" + $('#package_price').val() + "",
              success:function (snTransactionId) {
                $('#invoice').val(snTransactionId);
                $('#frm' + frm).submit();
              }
            });
          }
        }
        else {
          alert('Please Accept Term and Condition');
          return false;
        }
      }
      else {
        alert('Please Choose a Plan');
        return false;
      }
    }
    else // For Pay via paypal pro.
    {
      var snCreditCardNumber = jQuery.trim($('#credit_card_number').val());
      var snCvvNumber = jQuery.trim($('#cvv_number').val());
      var snMonth = $('#expiry_date_month').val();
      var snYear = $('#expiry_date_year').val();
      var oDate = new Date();
      var bSelectPlan = true;
      var bCreditCardNumber = true;
      var bCvvNumber = true;
      var bExpireCreditCard = true;
      if (numberOfItems <= 0) {
        alert('Please Choose a Plan');
        bSelectPlan = false;
        return bSelectPlan;
      }
      if (snCreditCardNumber == '') {
        alert('Please enter your credit card number');
        bCreditCardNumber = false;
        return bCreditCardNumber;
      }
      else if (snCreditCardNumber != '') {
        if (isNaN(snCreditCardNumber) || snCreditCardNumber.length < 16) {
          alert('Please enter valid credit card number');
          bCreditCardNumber = false;
          return bCreditCardNumber;
        }
      }
      if (snCvvNumber == '') {
        alert('Please enter your card verification number');
        bCvvNumber = false;
        return bCvvNumber;
      }
      else if (snCvvNumber != '') {
        if (isNaN(snCvvNumber) || snCvvNumber.length < 3) {
          alert('Please enter valid card verification number');
          bCvvNumber = false;
          return bCvvNumber;
        }
      }
      if (snMonth - 1 < oDate.getMonth() && snYear == oDate.getFullYear()) {
        alert('Your credit card has been expired');
        bExpireCreditCard = false;
        return bExpireCreditCard;
      }
      if (!$('#term_condition').is(':checked')) {
        alert('Please Accept Term and Condition');
        bTermCondition = false;
        return bTermCondition;
      }
      if (bSelectPlan > 0 && bCreditCardNumber && bCvvNumber && bExpireCreditCard && bTermCondition) {
        $('#frm' + frm).submit();
      }
    }
  }
  function isFloat(value) {
    if (isNaN(value) || value.indexOf(".") < 0) {
      return false;
    }
    else {
      return parseFloat(value);
    }
  }

 */ ?>
  function setCCDisplay(value) {
    console.log(value);
    if ('cc' == value) {
      $('#fieldset_cc').show();
    }
    else {
      $('#fieldset_cc').hide();
    }
  }

  $(document).ready(function () {
    $('input[name*=payment_type]').click(function () {
      setCCDisplay($('input[name*=payment_type]:checked').val());
    });
    setCCDisplay($('input[name*=payment_type]:checked').val());
  });
</script>
<?php cq_end_javascript_tag(); ?>
