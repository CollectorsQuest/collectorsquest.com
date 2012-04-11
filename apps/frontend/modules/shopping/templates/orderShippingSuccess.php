<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping & Handling'), __('Payment'), __('Review')) , 'active' => 1)
  );
?>
<br/><br/>

<form action="<?= url_for('@shopping_order_shipping?uuid='. $shopping_order->getUuid()); ?>" class="form-horizontal">
  <fieldset>
    <legend>Contact Details</legend>
    <div class="control-group">
      <label class="control-label" for="inputError">Email Address *</label>
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on"><i class="icon-envelope"></i></span>
          <input class="span4" type="text" value="<?= $shopping_order->getBuyerEmail(); ?>" style="margin-left: -4px;">
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

    <br/>
    <div class="row-fluid">
    <?php foreach ($shipping_addresses as $shipping_address): ?>
      <div class="span4 well">
        <p><?= $shipping_address->getFullName(); ?></p>
        <p><?= $shipping_address->getAddressLine1(); ?></p>
        <p>
          <?= $shipping_address->getCity(); ?>,
          <?= $shipping_address->getStateRegion(); ?>
          <?= $shipping_address->getZipPostcode(); ?>
        </p>
        <p style="margin-bottom: 0;"><?= $shipping_address->getCountry(); ?></p>
      </div>
      <div class="span4 well">
        <?= $shipping_address->getFullName(); ?>
      </div>

      <div class="span4">
        <a href="" class="btn btn-large">
          <i class="icon icon-plus"></i> New Address
        </a>
      </div>
    <?php endforeach; ?>
    </div>

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

  <div class="pull-right">
    <button type="submit" class="btn btn-large btn-primary">Continue to Payment</button>
  </div>
</form>
