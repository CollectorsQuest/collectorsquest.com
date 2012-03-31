<script type="text/javascript" src="//www.paypalobjects.com/js/external/dg.js"></script>

<div style="text-align: center; margin: 100px auto;">
  <form action= "https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
    <input id="type" type="hidden" name="expType" value="light"/>
    <input id="paykey" type="hidden" name="paykey" value="<?= $pay_key; ?>"/>
    <button class="btn btn-large" id="submitBtn" value="Pay with PayPal">Pay with PayPal</button>
  </form>
</div>


<script type="text/javascript">
  $(document).ready(function()
  {
    var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'submitBtn' });
  });
</script>
