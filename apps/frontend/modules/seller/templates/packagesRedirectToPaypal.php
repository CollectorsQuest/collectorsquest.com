<?php
  /* @var $packageTransaction PackageTransaction */
  /* @var $promotion Promotion */
  $package  = $packageTransaction->getPackage();
  $discount = $package->getPackagePrice() - $packageTransaction->getPackagePrice();
?>

<div class="row-fluid spacer-top-30">
  <div class="span4">
    <div class="pull-right">
      <?= cq_image_tag('frontend/logo/175x80.png'); ?>
    </div>
  </div>
  <div class="span3" style="text-align: center;">
    <center>
      <?= cq_image_tag('loading.arrows.gif', array('class' => 'block spacer-top-30')); ?>
    </center>
  </div>
  <div class="span5">
    <div class="pull-left">
      <?= cq_image_tag('frontend/paypal-logo-175.png'); ?>
    </div>
  </div>
</div>

<br>
<h3 class="text-center spacer-top-20">Please wait while we redirect you to PayPal</h3>
<p id="manual" class="brown text-center js-hide">
  If you're not redirected within 5 seconds
  <?= link_to_function('click here.', "$('input#submit').click();"); ?>
</p>

<form action="<?= sfConfig::get('app_paypal_url'); ?>" name="frmpaypal" id="frmpaypal" method="post">
  <input type="hidden" name="item_number" value="PKG-<?= $package->getId() ?>" />
  <input type="hidden" name="item_name" value="<?= (string) $packageTransaction->getPackage() ?>" />
  <input type="hidden" name="amount" value="<?= $package->getPackagePrice() ?>" />
  <input type="hidden" name="invoice" value="<?= $packageTransaction->getId() ?>" />

  <?php if ($discount): ?>
    <input type="hidden" name="discount_amount" value="<?= $discount ?>" />
  <?php endif; ?>
  <?php if ($promotion): ?>
    <input type="hidden" name="custom" value="<?= $promotion->getId() ?>" />
  <?php endif; ?>

  <input type="hidden" name="shipping" value="0" />
  <input type="hidden" name="shipping2" value="0" />
  <input type="hidden" name="no_shipping" value="1" />
  <input type="hidden" name="tax" value="0" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="business" value="<?= sfConfig::get('app_paypal_merchant_account') ?>" />
  <input type="hidden" name="currency_code" value="USD" />
  <input type="hidden" name="currency" value="USD" />
  <input type="hidden" name="return" value="<?= $return_url ?>" />
  <input type="hidden" name="cancel_return" value="<?= $cancel_return_url ?>" />
  <input type="hidden" name="notify_url" value="<?= $notify_url ?>" />

  <input type="hidden" name="cmd" value="_xclick">
  <input type="submit" id="submit" value="Continue to PayPal →" style="display: block; margin: 0 auto;" />
  <script>
    $(document).ready(function()
    {
      $('#manual').show();
      $('#submit').hide().click();
    });
  </script>
  <noscript>
    <p>Javascript is disabled. Please click on the 'Continue to PayPal' button to be redirected to PayPal.</p>
  </noscript>
</form>
