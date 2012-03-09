<?php
/* @var $packageTransaction PackageTransaction */

$package = $packageTransaction->getPackage();
?>
<h3 style="text-align: center; margin-top: 20px;">Redirecting to PayPal ...</h3>
<form action="<?php echo sfConfig::get("app_paypal_url"); ?>" name="frmpaypal" id="frmpaypal" method="post">
  <?php
  // Paypal Details
  echo input_hidden_tag('item_number', $packageTransaction->getId());
  echo input_hidden_tag('item_name', (string)$packageTransaction->getPackage());
  echo input_hidden_tag('custom');
  echo input_hidden_tag('amount', $package->getPackagePrice());
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
  echo input_hidden_tag('return', url_for1(sfConfig::get("app_paypal_return_url"), true)); //TODO Return url
  echo input_hidden_tag('cancel_return', url_for1(sfConfig::get('app_paypal_cancel_url'), true)); //TODO Cancel payment
//  echo input_hidden_tag('notify_url', url_for1(sfConfig::get("app_paypal_notify_url"), true));
  ?>
  <input type="submit" value="Continue" style="display: block; margin: 0 auto;" />
  <script type="text/javascript">
    document.frmpaypal.submit();
  </script>
  <noscript>
    <p>Javascript is disabled. Please click on the 'Continue' button to be redirected to PayPal.</p>
  </noscript>
</form>
