<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping'), __('PayPal Payment'), __('Review')) , 'active' => 2)
  );
?>

<div style="text-align: center; margin: 100px auto;">
  <form action= "https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
    <input id="type" type="hidden" name="expType" value="light"/>
    <input id="paykey" type="hidden" name="paykey" value="<?= $pay_key; ?>"/>
    <input type="image" id="submitBtn" src="//www.paypal.com/en_US/i/btn/btn_dg_pay_w_paypal.gif"/>
  </form>
</div>

<script src="//www.paypalobjects.com/js/external/dg.js"></script>
<script>
  $(document).ready(function()
  {
    var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'submitBtn' });
    $('#submitBtn').click();
  });
</script>
