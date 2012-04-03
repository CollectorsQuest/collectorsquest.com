<?php
include_partial(
  'global/wizard_bar',
  array('steps' => array(1 => __('Add to Cart'), __('Payment'), __('Review')) , 'active' => 2)
);
?>

<script type="text/javascript" src="//www.paypalobjects.com/js/external/dg.js"></script>

<div style="text-align: center; margin: 100px auto;">
  <form action= "https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
    <input id="type" type="hidden" name="expType" value="light"/>
    <input id="paykey" type="hidden" name="paykey" value="<?= $pay_key; ?>"/>
    <input type="image" id="submitBtn" src="https://www.paypal.com/en_US/i/btn/btn_dg_pay_w_paypal.gif"/>
  </form>
</div>


<script type="text/javascript">
  $(document).ready(function()
  {
    var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'submitBtn' });
  });
</script>
