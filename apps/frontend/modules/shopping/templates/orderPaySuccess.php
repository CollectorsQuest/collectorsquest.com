<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Add to Cart'), __('Payment'), __('Review')) , 'active' => 2)
  );
?>

<br/><br/>
<form class="form-horizontal">
<fieldset>
  <legend>Contact details</legend>
  <div class="control-group">
    <label class="control-label" for="inputError">Email Address *</label>
    <div class="controls">
      <input type="text" id="inputError">
      <p class="help-block">We need your email address so that we can send you an order confirmation</p>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputError">Telephone Number</label>
    <div class="controls">
      <input type="text" id="inputError">
    </div>
  </div>
</fieldset>

<fieldset>
  <legend>Shipping Address</legend>

  <?php foreach ($shipping_addresses as $shipping_address): ?>

  <?php endforeach; ?>

  <div class="control-group">
    <label class="control-label" for="inputError">Full name *</label>
    <div class="controls">
      <input type="text">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Address line 1 *</label>
    <div class="controls">
      <input type="text">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Address line 2 *</label>
    <div class="controls">
      <input type="text">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Town/City *</label>
    <div class="controls">
      <input type="text">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">County/State</label>
    <div class="controls">
      <input type="text">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Postcode/Zip *</label>
    <div class="controls">
      <input type="text">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Country</label>
    <div class="controls">
      <select></select>
    </div>
  </div>

</fieldset>

<fieldset>
  <legend>Form validation error, warning and success</legend>
  <div class="control-group error">
    <label class="control-label" for="inputError">Input with error</label>
    <div class="controls">
      <input type="text" id="inputError">
      <span class="help-inline">Please correct the error</span>
    </div>
  </div>
  <div class="control-group warning">
    <label class="control-label" for="inputWarning">Input with warning</label>
    <div class="controls">
      <input type="text" id="inputWarning">
      <span class="help-inline">Something may have gone wrong</span>
    </div>
  </div>
  <div class="control-group success">
    <label class="control-label" for="inputSuccess">Input with success</label>
    <div class="controls">
      <input type="text" id="inputSuccess">
      <span class="help-inline">Successfully entered</span>
    </div>
  </div>
  <div class="control-group success">
    <label class="control-label" for="selectError">Select with success</label>
    <div class="controls">
      <select id="selectError">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
      </select>
      <span class="help-inline">Successfully selected</span>
    </div>
  </div>
</fieldset>
</form>

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
  });
</script>
