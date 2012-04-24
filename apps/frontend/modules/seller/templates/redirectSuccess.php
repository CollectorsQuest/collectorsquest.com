<?php
/* @var $packageTransaction PackageTransaction */
/* @var $promotion Promotion */
$package  = $packageTransaction->getPackage();
$discount = $package->getPackagePrice() - $packageTransaction->getPackagePrice();
?>
<h3 style="text-align: center; margin-top: 20px;">Redirecting to PayPal ...</h3>
<form action="<?php echo sfConfig::get("app_paypal_url"); ?>" name="frmpaypal" id="frmpaypal" method="post">
  <input type="hidden" name="item_number" value="" />
  <input type="hidden" name="item_name" value="<?php echo (string)$packageTransaction->getPackage() ?>" />
  <input type="hidden" name="custom" />
  <input type="hidden" name="amount" value="<?php echo $package->getPackagePrice() ?>" />
  <?php if ($discount): ?>
  <input type="hidden" name="discount_amount" value="<?php echo $discount ?>" />
  <?php endif; ?>
  <input type="hidden" name="invoice" value="<?php echo $packageTransaction->getId() ?>" />
  <input type="hidden" name="cmd" value="_xclick">
  <?php if ($promotion): ?>
  <input type="hidden" name="on0" value="<?php echo $promotion->getId() ?>" />
  <?php endif; ?>
  <input type="hidden" name="shipping" value="0" />
  <input type="hidden" name="shipping2" value="0" />
  <input type="hidden" name="no_shipping" value="1" />
  <input type="hidden" name="tax" value="0" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="business" value="<?php echo sfConfig::get('app_paypal_merchant_account') ?>" />
  <input type="hidden" name="currency_code" value="<?php echo sfConfig::get('app_paypal_currency') ?>" />
  <input type="hidden" name="currency" value="<?php echo sfConfig::get('app_paypal_currency') ?>" />
  <input type="hidden" name="return" value="<?php echo url_for1('manage_profile', true) ?>" />
  <input type="hidden" name="cancel_return" value="<?php echo url_for('seller_cancel_payment', array('id'=> $packageTransaction->getId()), true) ?>" />
  <input type="hidden" name="notify_url" value="<?php echo url_for1('seller_callback_ipn', true) ?>" />

  <input type="submit" id="submit" value="Continue" style="display: block; margin: 0 auto;" />
  <script type="text/javascript">
    $(document).ready(function(){
      $('input#submit').hide().click();
    });
  </script>
  <noscript>
    <p>Javascript is disabled. Please click on the 'Continue' button to be redirected to PayPal.</p>
  </noscript>
</form>
