<?php
  /* @var $packagesForm SellerPackagesForm */
  $tainted_form_values = $sf_request->getParameter($packagesForm->getName());
  $package_id_value = isset($tainted_form_values['package_id'])
    ? $tainted_form_values['package_id']
    : $sf_params->get('package');
?>

<?php slot('sidebar_300'); ?>
<div class="seller-info-box-yellow">
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
        <strong>sign up now</strong>
      </a>!
    </dd>
  </dl>
</div>
<?php end_slot(); ?>

<?php if ($packagesForm->hasGlobalErrors()): ?>
  <?= $packagesForm->renderGlobalErrors(); ?>
<?php elseif (IceGateKeeper::locked('mycq_seller_pay')): ?>
  <div class="alert alert-info">
    The Marketplace is almost ready! If you're interested in becoming a seller,
    please email <?= mail_to('info@collectorsquest.com', 'info@collectorsquest.com'); ?>
    and we'll add you to our notification list.
  </div>
<?php endif; ?>

<form action="<?= url_for('seller_packages')?>" method="post""
      id="form-seller-packages" class="form-horizontal" novalidate="novalidate">
  <?= $packagesForm->renderHiddenFields() ?>

  <?php
    if (
      IceGateKeeper::open('mycq_seller_pay') &&
      isset($packagesForm['pending_transaction_confirm']) &&
      $packagesForm->isError('pending_transaction_confirm')
    ):
  ?>
  <div class="pending-transaction-holder">
    <div id="pending-transaction-warning" class="alert alert-info">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <p>
         <h4>Warning: Your recent transaction with us is still pending!</h4><br>
         Did you complete payment with PayPal? We're not sure yet,
         and we're waiting to receive an answer for the status of your payment.
      </p>
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
  <br>
  <?php endif; ?>

    <?php
    $link = link_to(
      'Show me other packages', $sf_request->getUri() ,
      array('class' => 'show-radio-list text-v-middle link-align', 'anchor' => '')
    );

    cq_sidebar_title(
      'Which package is right for you?', $link,
      array('left' => 6, 'right' => 6, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
    ?>

  <div class="control-group packages-wrapper">
    <div class="choice-packages">
      <div class="radio_list">
        <label class="radio hide">
          <input required="required" name="packages[package_id]" type="radio" value="1" <?= 1 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_1">
          <span class="package1 Chivo webfont tooltip-position-right" rel="tooltip" title="$2.50 per listing">
            1 listing <span class="blue pull-right">$2.50</span>
          </span>
        </label>
        <label class="radio">
          <input required="required" name="packages[package_id]" type="radio" value="2" <?= 2 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_2">
          <span class="package2 Chivo webfont tooltip-position-right" rel="tooltip" title="$2.00 per listing">
            10 listings <span class="blue pull-right">$20</span>
          </span>
        </label>
        <label class="radio hide">
          <input required="required" name="packages[package_id]" type="radio" value="3" <?= 3 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_3">
          <span class="package3 Chivo webfont tooltip-position-right" rel="tooltip" title="$1.50 per item">
            100 listings <span class="blue pull-right">$150</span>
          </span>
        </label>
        <label class="radio hide">
          <input required="required" name="packages[package_id]" type="radio" value="6" <?= 6 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_4">
          <span class="package4 Chivo webfont" title="unlimited items">
            <span class="red-bold">UNLIMITED</span> listings <span class="blue pull-right">only $250</span>
          </span>
          <!--<span class="price-per-item red">unlimited items</span>-->
        </label>
      </div>
      <?= $packagesForm['package_id']->renderError(); ?>
    </div>
  </div>
  <?php /*
  <div class="control-group packages-wrapper">
      <div class="choice-packages">
        <div class="radio_list">
          <label class="radio">
            <input required="required" name="packages[package_id]" type="radio" value="1" <?= 1 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_1">
            <span class="package1 Chivo webfont tooltip-position-top" rel="tooltip" title="$2.50 per item">1 listing <span class="blue pull-right">$2.50</span></span>
          </label>
          <label class="radio">
            <input required="required" name="packages[package_id]" type="radio" value="2" <?= 2 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_2">
            <span class="package2 Chivo webfont tooltip-position-top" rel="tooltip" title="$2.00 per item">10 listings <span class="blue pull-right">$20</span></span>
          </label>
          <label class="radio">
            <input required="required" name="packages[package_id]" type="radio" value="3" <?= 3 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_3">
            <span class="package3 Chivo webfont tooltip-position-top" rel="tooltip" title="$1.50 per item">100 listings <span class="blue pull-right">$150</span></span>
          </label>
          <label class="radio">
            <input required="required" name="packages[package_id]" type="radio" value="6" <?= 6 == $package_id_value ? 'checked' : '' ?> id="packages_package_id_4">
            <span class="package4 Chivo webfont tooltip-position-top" rel="tooltip" title="unlimited items"><span class="red-bold">UNLIMITED</span> listings <span class="blue pull-right">only $250</span></span>
            <!--<span class="price-per-item red">unlimited items</span>-->
          </label>
        </div>
        <?= $packagesForm['package_id']->renderError(); ?>
      </div>
  </div>
  */ ?>
  <fieldset>
    <div class="control-group discount-code-wrapper">
      <?= $packagesForm['promo_code']->renderLabel('Have a promo code?', array('class'=> 'control-label')) ?>
      <div class="controls form-inline">
        <?= $packagesForm['promo_code']->render() ?>
        <button type="submit" name="applyPromo" id="applyPromo3" class="btn btn-primary"
                value="applyPromo" formnovalidate="formnovalidate">
          Apply Discount
        </button>
        <?= $packagesForm['promo_code']->renderError() ?>
        <?php if (!empty($discountMessage)): ?>
        <span style="color: green; font-weight: bold;"><?=$discountMessage ?></span>
        <?php endif; ?>
      </div>
    </div>
    <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
    <?php cq_section_title('How would you like to pay?'); ?>
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
  <fieldset id="credit-card-info" class="js-hide cf">

    <div class="credit-card-wrapper">
      <div class="gray-bg">
        Credit/Debit Card
      </div>
      <div class="credit-card-inner">
        <div class="row-fluid">
          <div class="span8">
            <small>Card number:</small>
          </div>
          <div class="span4">
            <small>CVV/CSC number:</small>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span8 cc_number">
            <?= $packagesForm['cc_number']->render() ?>
          </div>
          <div class="span4 cvv_number">
            <?= $packagesForm['cvv_number']->render(); ?>
            <span class="help" rel="tooltip"
                  title="For <strong>Visa, MasterCard, and Discover</strong> cards,
                  the card code is the last 3 digit number located on the back of
                  your card on or above your signature line. For an <strong>American Express</strong>
                  card, it is the 4 digits on the FRONT above the end of your card number.">
              <i class="icon-question-sign"></i>
            </span>
          </div>
        </div>
        <?= $packagesForm['cc_number']->renderError() ?>
        <?= $packagesForm['cvv_number']->renderError(); ?>

        <div class="row-fluid spacer-top-15">
          <div class="span8">
            <small>Cardholder's name:</small>
          </div>
          <div class="span4">
            <small>Expiration date:</small>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span8 cc_name">
            <?= $packagesForm['first_name']->render() ?>
            <?= $packagesForm['last_name']->render() ?>
          </div>
          <div class="span4">
            <?= $packagesForm['expiry_date']->render(array('class'=> 'input-mini pull-left')) ?>
          </div>
        </div>
        <?= $packagesForm['first_name']->renderError() ?>
        <?= $packagesForm['last_name']->renderError() ?>
        <?= $packagesForm['expiry_date']->renderError() ?>
      </div>
    </div>

    <?= $packagesForm['street']->renderRow() ?>
    <?= $packagesForm['city']->renderRow() ?>
    <?= $packagesForm['state']->renderRow() ?>
    <?= $packagesForm['zip']->renderRow() ?>
    <?= $packagesForm['country']->renderRow() ?>
  </fieldset>
  <?php endif; ?>

  <div class="agreement-checks control-group spacer-bottom-reset">
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
        <?= $packagesForm['fyi']->render(array('style' => 'margin-bottom: 25px; float: left; margin-right: 5px; margin-top: 3px;')) ?>
        I acknowledge that all payments made to me for items sold on
        Collectors Quest are processed through PayPal<sup>®</sup>.
      </label>
      <?= $packagesForm['fyi']->renderError() ?>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Purchase Package</button>
    <a href="<?= url_for('mycq') ?>" class="btn spacer-left">
      Cancel
    </a>
  </div>
</form>

<script>
$(document).ready(function() {
  'use strict';

  var $payment_type_input = $('input[name="packages[payment_type]"]');
  var $credit_card_info = $('#credit-card-info');

  // display or hide extra CC info fields based on the selected payment_type
  var display_cc_info = function (payment_type) {
    if ('cc' === payment_type) {
      $credit_card_info.slideDown();
    } else if ('paypal' === payment_type) {
      $credit_card_info.slideUp();
    }
  };

  // first setup state on page load
  display_cc_info($('input:checked[name="packages[payment_type]"]').val());

  // and then call display_cc_info on every change
  $payment_type_input.on('change', function(){
    display_cc_info($(this).val());
  });

  $('#pending-transaction-warning').effect("highlight", {}, 3000);

  $('#form-seller-packages').submit(function()
  {
    $(this).showLoading();
    $('.btn-primary', $(this)).button('loading');
  });

  //radio box checked on click
  $("input:checked").parent().addClass('selected');

  $(":radio").on('change', function () {
    if ($(this).prop('checked')) {
      $(':radio[name="' + $(this).attr('name') + '"]').parent().removeClass('selected');
      $(this).parent().addClass('selected');
    }
  });

  $(":checkbox").on('change', function () {
    if ($(this).prop('checked')) {
      $(this).parent().addClass('selected');
    } else {
      $(this).parent().removeClass('selected');
    }
  });

  $(this).find("input:checkbox").click()

  $(".show-radio-list").click(function(){
    $(".radio_list .radio").show();
    $(this).hide();
  })


});
</script>
