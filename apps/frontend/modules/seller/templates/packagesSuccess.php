<?php
  /* @var $packagesForm SellerPackagesForm */
  $tainted_form_values = $sf_request->getParameter($packagesForm->getName());
  $package_id_value = isset($tainted_form_values['package_id'])
    ? $tainted_form_values['package_id']
    : 0;
?>

<?php if ($packagesForm->hasGlobalErrors()): ?>
  <?= $packagesForm->renderGlobalErrors(); ?>
<?php elseif (IceGateKeeper::locked('mycq_seller_pay')): ?>
<div class="alert alert-info">
  The market is currently in private beta testing mode.
  If you have received a promo code for participation, please enter it below.
  If you'd like to be a beta tester, please email
  <?= mail_to('info@collectorsquest.com', 'info@collectorsquest.com'); ?> for more information.
</div>
<?php endif; ?>

<div class="row-fluid">
  <div class="span4 seller-info-box-yellow">
    <h3>Why Sell on Collectors Quest?</h3>
    <dl class="text-container">

      <dt>Broader Exposure</dt>
      <dd>
        Have your sale items paired with related and relevant content across the site.
        Check out any page on the site and you'll see!
      </dd>

      <dt>Flat Rate with No Transaction Fees</dt>
      <dd>
        No fancy math needed! Annual subscribers can sell as many items as
        they'd like at no additional cost.
      </dd>

      <dt>List for Six Months</dt>
      <dd>
        Buy Credits that last for one full year. Once an item is marked for sale,
        it remains in the Market for up to 6 months.
      </dd>

      <dt>Payments Processed by PayPal<sup>®</sup></dt>
      <dd>
        Collectors Quest uses PayPal<sup>®</sup> to process all payments made to sellers on our site.
        If you don't have a PayPal<sup>®</sup> account, make sure to
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_registration-run"
           target="_blank">
          sign up now
        </a>!
      </dd>
    </dl>
  </div>
  <div class="span8">
    <form action="<?= url_for('seller_packages')?>" method="post"
          id="form-seller-packages" class="form-horizontal" novalidate="novalidate">
      <?= $packagesForm->renderHiddenFields() ?>

      <?php if (
          IceGateKeeper::open('mycq_seller_pay') &&
          isset($packagesForm['pending_transaction_confirm']) &&
          $packagesForm->isError('pending_transaction_confirm')
        ): ?>
      <div class="pending-transaction-holder">
        <div id="pending-transaction-warning" class="alert alert-info">
          <a class="close" data-dismiss="alert" href="#">×</a>
          <p>
             <h4>Warning: Your recent transaction with us is still pending!</h4>
             Did you complete payment with your payment service provider?
             We're not sure yet, and we're waiting to receive an answer from them.
          </p>
          <br />

          <p>
            If you continue, and are purchasing any items a second time,
            you risk being charged twice.
          </p>
        </div>

        <label class="checkbox spacer-left-5">
          <?= $packagesForm['pending_transaction_confirm']->render(); ?>
          <strong>I understand. Continue with this transaction.</strong>
        </label>
        <div class="help-block spacer-left-25">
          Need help? <?= link_to('Contact us here!', '/pages/contact-us/', array('target' => '_blank')); ?>
        </div>
      </div>
      <?php endif; ?>

      <?php cq_sidebar_title('Which package is right for you'); ?>

      <div class="control-group">
          <div class="choice-packages">
            <div class="radio_list">
              <label class="radio">
                <input required="required" name="packages[package_id]" type="radio" value="1" <?= 1 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_1">
                <span class="package1 Chivo webfont">1 listing <span class="red pull-right">$2.50</span></span>
                <span class="price-per-item">$2.50 per item</span>
              </label>
              <label class="radio">
                <input required="required" name="packages[package_id]" type="radio" value="2" <?= 2 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_2">
                <span class="package2 Chivo webfont">10 listings <span class="red pull-right">$20</span></span>
                <span class="price-per-item">$2.00 per item</span>
              </label>
              <label class="radio">
                <input required="required" name="packages[package_id]" type="radio" value="3" <?= 3 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_3">
                <span class="package3 Chivo webfont">100 listings <span class="red pull-right">$150</span></span>
                <span class="price-per-item">$1.50 per item</span>
              </label>
              <label class="radio">
                <input required="required" name="packages[package_id]" type="radio" value="6" <?= 6 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_4">
                <span class="package4 Chivo webfont"><span class="red-bold">UNLIMITED</span> listings <span class="red pull-right">only $250</span></span>
                <span class="price-per-item red">unlimited items</span>
              </label>
            </div>
            <?= $packagesForm['package_id']->renderError(); ?>
          </div>
      </div>

      <fieldset>
        <div class="control-group">
          <?= $packagesForm['promo_code']->renderLabel(null, array('class'=> 'control-label')) ?>
          <div class="controls form-inline">
            <?= $packagesForm['promo_code']->render() ?>
            <button type="submit" name="applyPromo" id="applyPromo3" class="btn btn-primary"
                    value="applyPromo" formnovalidate="formnovalidate">
              Apply
            </button>
            <?= $packagesForm['promo_code']->renderError() ?>
            <?php if (!empty($discountMessage)): ?>
            <span style="color: green; font-weight: bold;"><?=$discountMessage ?></span>
            <?php endif; ?>
          </div>
        </div>
        <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
        <?php cq_sidebar_title('How would you pay'); ?>
          <div class="payment-type">
            <div class="control-group">
              <div class="controls-inline clearfix">
                <?= $packagesForm['payment_type']->render() ?>
              </div>
              <?= $packagesForm['payment_type']->renderError() ?>
            </div>
          </div>
        <?php endif; ?>
      </fieldset>

      <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
      <fieldset id="credit-card-info" class="js-hide clearfix">
        <?= $packagesForm['cc_type']->renderRow() ?>
        <?= $packagesForm['cc_number']->renderRow() ?>
        <?= $packagesForm['expiry_date']->renderRow(array('class'=> 'input-mini pull-left')) ?>
        <div class="control-group ">
          <label class="control-label" for="<?= $packagesForm['cvv_number']->renderId(); ?>">
            <?= $packagesForm['cvv_number']->renderLabelName(); ?>
          </label>
          <div class="controls">
            <div class="cid_icon_generic">
              <img src="/images/frontend/cid_icon_generic.gif" alt="ccv number">
            </div>
            <?= $packagesForm['cvv_number']->render(); ?>
          </div>
        </div>
        <?= $packagesForm['first_name']->renderRow() ?>
        <?= $packagesForm['last_name']->renderRow() ?>
        <?= $packagesForm['street']->renderRow() ?>
        <?= $packagesForm['city']->renderRow() ?>
        <?= $packagesForm['state']->renderRow() ?>
        <?= $packagesForm['zip']->renderRow() ?>
        <?= $packagesForm['country']->renderRow() ?>
      </fieldset>
      <?php endif; ?>

      <div class="control-group" style="margin-bottom: 0;">
        <label class="control-label control-label">&nbsp;</label>
        <div class="controls form-inline reset-label-colors">
          <label for="<?= $packagesForm['terms']->renderId() ?>" class="radio inline">
            <?= $packagesForm['terms']->render() ?>&nbsp;
            <?php
            echo sprintf(
              'I accept the %s set forth by Collectors Quest.',
              urldecode(link_to(
                'terms and conditions', '@blog_page?slug=terms-and-conditions',
                array('target' => '_blank')
              ))
            );
            ?>
          </label>
          <?= $packagesForm['terms']->renderError() ?>
        </div>
        <div class="controls form-inline reset-label-colors">
          <label for="<?= $packagesForm['fyi']->renderId() ?>" class="radio inline">
            <?= $packagesForm['fyi']->render(array('style' => 'margin-bottom: 25px; float: left; margin-right: 5px; margin-top: 4px;')) ?>
            I acknowledge that all payments made to me for items sold on
            Collectors Quest are processed through PayPal<sup>®</sup>.
          </label>
          <?= $packagesForm['fyi']->renderError() ?>
        </div>
      </div>

      <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
      <h5 class="info-text">* To avoid interruption of service, annual subscriptions
        automatically<br> renew at the end of the subscription period</h5>
      <?php endif; ?>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Purchase Package</button>
        <a href="<?= url_for('mycq') ?>" class="btn spacer-left">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>


<script>
$(document).ready(function() {
  'use strict';

  var $payment_type_input = $('input[name="packages[payment_type]"]');
  var $credit_card_info = $('#credit-card-info');

  // display or hide extra CC info fields based on the selected payment_type
  var display_cc_info = function(payment_type) {
    console.log(payment_type);
    if ('cc' === payment_type) {
      $credit_card_info.slideDown();
    } else if ('paypal' === payment_type) {
      $credit_card_info.slideUp();
    }
  }

  // first setup state on page load
  display_cc_info($('input:checked[name="packages[payment_type]"]').val());

  // and then call display_cc_info on every change
  $payment_type_input.on('change', function(){
    display_cc_info($(this).val());
  });

  $('#pending-transaction-warning').effect("highlight", {}, 3000)
});
</script>
