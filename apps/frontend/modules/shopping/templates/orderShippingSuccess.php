<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping & Handling'), __('Payment'), __('Review')) , 'active' => 1)
  );
?>
<br/><br/>

<form class="form-horizontal">
<fieldset>
  <legend>Contact details</legend>
  <div class="control-group">
    <label class="control-label" for="inputError">Email Address *</label>
    <div class="controls">
      <div class="input-prepend">
        <span class="add-on"><i class="icon-envelope"></i></span>
        <input class="span4" type="text" style="margin-left: -4px;">
      </div>

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
