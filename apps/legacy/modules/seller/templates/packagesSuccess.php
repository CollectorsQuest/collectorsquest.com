<?php
/* @var $packagesForm SellerPackagesForm */
/* @var $freeSubscription bool */
/* @var $sf_request sfWebRequest */
/* @var $sf_user cqUser */
/* @var $discountMessage string */

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
        <?php echo $packagesForm->renderHiddenFields(); ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="offerright">
          <tr>
            <td>
              <fieldset>
                <legend>Choose a Plan</legend>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <?php echo $packagesForm['package_id']->renderError(); ?>
                  <?php echo $packagesForm['package_id']->render(); ?>
                  <tr>
                    <th>Promotion Code:</th>
                    <td>
                      <?php echo $packagesForm['promo_code']->render(); ?>
                      <button type="submit" name="submit" value="applyPromo" class="submit"
                              style="cursor: pointer; border: 1px solid; float: none; display: inline-block;" title="Check promo code">Apply
                      </button>
                      <?php if (!empty($discountMessage)): ?>
                      <ul class="error_list">
                        <li class="success"><?php echo $discountMessage; ?></li>
                      </ul>
                      <?php endif; ?>
                      <?php if ($sf_user->hasFlash('promo')): ?>
                      <ul class="error_list">
                        <li><?php echo $sf_user->getFlash('promo'); ?></li>
                      </ul>
                      <?php endif; ?>
                      <?php if ($packagesForm['promo_code']->hasError()): ?>
                      <br />
                      <?php echo $packagesForm['promo_code']->renderError(); ?>
                      <?php endif; ?>
                    </td>
                  </tr>
                </table>
                <?php if (!$freeSubscription): ?>
                <?php /* @var $paymentType sfFormField */ ?>
                <?php echo $packagesForm['payment_type']->render(array('style'=> 'height: 24px;')); ?>
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
                </table>
                <?php if ($sf_user->hasFlash('msg_payment')): ?>
                  <ul class="error_list">
                    <li><?php echo $sf_user->getFlash('msg_payment'); ?></li>
                  </ul>
                  <?php endif; ?>
                <?php endif; ?>
                <h5 style="margin-top: 10px;">* To avoid interruption of service, annual subscriptions
                  automatically renew at the end of the subscription period</h5>
              </fieldset>
              <button type="submit" class="submit" style="margin-left: 190px;">
                <span><span>Sign up</span></span>
              </button>
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
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
