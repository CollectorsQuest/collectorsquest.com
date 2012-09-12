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
      'Which package is right for you?', isset($package_id_value) ? $link : '',
      array('left' => 7, 'right' => 5, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
    ?>

  <div class="control-group packages-wrapper">
    <div class="choice-packages">
      <div class="radio_list">
        <?php $package_labels = PackagePeer::getAllPackageLabelsForSelectById(
          isset($promotion) ? $promotion : null,
          array(
            'template' =>
              '<span class="%package_id_class% Chivo webfont tooltip-position-right label-holder" data-actual-price="%package_price_discounted%" rel="tooltip" title="%price_per_item% per item">
                %num_listings%
                <span class="spacer-left-5 green pull-right">%package_price_discounted%</span>
                <span class="blue pull-right %discounted_class%">%package_price%</span>
              </span>',
            'discount_class' => 'strikethrough',
        )); ?>
        <?php foreach ($package_labels as $package_id => $package_label): ?>
        <label class="radio <?= isset($package_id_value) && $package_id != $package_id_value ? 'hide' : '' ?>">
          <input required="required" name="packages[package_id]" type="radio"
            value="<?= $package_id; ?>"
            <?= $package_id == $package_id_value ? 'checked' : '' ?>
            id="packages_package_id_<?= $package_id; ?>"
            class="package-input"
          >
          <?= $package_label ?>
        </label>
        <?php endforeach; ?>
      </div>
      <?= $packagesForm['package_id']->renderError(); ?>
    </div>
  </div>

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
        <?php if (isset($discount_message)): ?>
        <br />
        <span style="color: green; font-weight: bold;"><?= $discount_message ?></span>
        <?php endif; ?>
      </div>
    </div>
    <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
    <?php cq_section_title('How would you like to pay?', null, array('id' => 'payment-header')); ?>
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
      <label for="<?= $packagesForm['terms']->renderId() ?>" class="checkbox">
        <?= $packagesForm['terms']->render() ?>&nbsp;
        <?php
          echo sprintf(
            'I accept the %s set forth by Collectors Quest.',
            link_to(
              'terms and conditions', 'blog_page',
              array('slug' => 'terms-and-conditions'), array('target' => '_blank')
            )
          );
        ?>
      </label>
      <?= $packagesForm['terms']->renderError() ?>
    </div>
    <div class="controls form-inline reset-label-colors">
      <label for="<?= $packagesForm['fyi']->renderId() ?>" class="checkbox">
        <?= $packagesForm['fyi']->render(array('class' => 'checkbox-indent')) ?>
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
  var $packages = $('input[name="packages[package_id]"]');

  // display or hide extra CC info fields based on the selected payment_type
  var display_cc_info = function () {
    var payment_type = $('input:checked[name="packages[payment_type]"]').val();
    if ('cc' === payment_type) {
      $credit_card_info.slideDown();
    } else if ('paypal' === payment_type) {
      $credit_card_info.slideUp();
    }
  };

  // setup payment type change event an trigger it for initial state
  $payment_type_input.on('change', function(){
    display_cc_info();
  }).trigger('change');

  $payment_type_input.on('change', function(){
    display_cc_info();
  }).trigger('change');

  // on packages change check if we have a free package and hide payment info
  // also trigger the event to get initial state
  $packages.on('change', function(e){
    var $checked = $packages.filter(':checked'),
        $label_holder = $checked.siblings('span.label-holder').eq(0);
    if ('$0' === $label_holder.data('actualPrice')) {
      $('div.payment-type').slideUp();
      $('#payment-header').slideUp();
      $credit_card_info.slideUp();
    } else {
      $('div.payment-type:hidden').slideDown();
      $('#payment-header:hidden').slideDown();
      display_cc_info();
    }
  }).trigger('change');

  $('#pending-transaction-warning').effect("highlight", {}, 3000);

  $('#form-seller-packages').submit(function()
  {
    $(this).showLoading();
    $('.btn-primary', $(this)).button('loading');
  });

  //radio box checked on click
  $('.package-input').click(function() {
    $(this).parents().find(".selected").removeClass("selected");

    if($(this).is(':checked'))  {
      $(this).parent().addClass('selected');
    }
  });

  $(".show-radio-list").click(function() {
    $(".radio_list .radio").show();
    $(this).hide();
  });


});
</script>
