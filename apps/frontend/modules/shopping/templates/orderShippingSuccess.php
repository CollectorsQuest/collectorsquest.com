<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping & Handling'), __('Payment'), __('Review')) , 'active' => 1)
  );
?>
<br/><br/>

<form action="<?= url_for('@shopping_order_shipping?uuid='. $shopping_order->getUuid()); ?>"
      method="post"
      class="form-horizontal">

  <fieldset>
    <legend>Contact Details</legend>
    <div class="control-group">
      <label class="control-label" for="inputError">Email Address *</label>
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on"><i class="icon-envelope"></i></span>
          <?= $form['buyer_email']->render(array('class' => 'span4', 'style' => 'margin-left: -4px;')) ?>
          <?= $form['buyer_email']->renderError() ?>
        </div>

        <p class="help-block">We need your email address so that we can send you an order confirmation</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Telephone Number</label>
      <div class="controls">
        <?= $form['buyer_phone']->render() ?>
        <?= $form['buyer_phone']->renderError() ?>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Shipping Address</legend>

    <br/>
    <div class="row-fluid">
      <?php foreach ($shipping_addresses as $shipping_address): ?>
      <div class="span4 well">
        <?= $shipping_address->getFullName(); ?><br/>
        <?= $shipping_address->getAddressLine1(); ?>
        <p>
          <?= $shipping_address->getCity(); ?>,
          <?= $shipping_address->getStateRegion(); ?>
          <?= $shipping_address->getZipPostcode(); ?>
        </p>
        <p style="font-weight: bold;"><?= $shipping_address->getCountryName(); ?></p>
        <a href="<?= url_for('@shopping_order_shipping?uuid='. $shopping_order->getUuid() .'&address_id='. $shipping_address->getId()); ?>" class="btn">
          Ship to this address
        </a>
      </div>
      <?php endforeach; ?>
      <div class="span4">
        <a href="" class="btn btn-large">
          <i class="icon icon-plus"></i> New Address
        </a>
      </div>
    </div>

    <?= isset($form['shipping_address']) ? $form['shipping_address'] : null ?>
  </fieldset>

  <div class="pull-right">
    <button type="submit" class="btn btn-large btn-primary">Continue to Payment</button>
  </div>

  <?= $form->renderHiddenFields(); ?>
</form>
