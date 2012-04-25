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
      <?= $form['buyer_email']->renderLabel(null, array('class' => 'control-label')); ?>
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on"><i class="icon-envelope"></i></span>
          <?= $form['buyer_email']->render(array('class' => 'span4', 'style' => 'margin-left: -4px;')) ?>
          <?= $form['buyer_email']->renderError() ?>
        </div>
      </div>
    </div>
    <div class="control-group">
      <?= $form['buyer_phone']->renderLabel(null, array('class' => 'control-label')); ?>
      <div class="controls">
        <?= $form['buyer_phone']->render() ?>
        <?= $form['buyer_phone']->renderError() ?>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>
      Shipping Address
      <?php if ($sf_user->isAuthenticated()): ?>
        <button type="submit" class="btn pull-right" style="font-size: 60%;" name="new_address">
          <i class="icon icon-plus"></i>
          &nbsp;Add new shipping address
        </button>
      <?php endif; ?>
    </legend>

    <br/>
    <?php if ($sf_user->isAuthenticated()): ?>
    <div class="row-fluid">

      <?php if (count($shipping_addresses) > 0): ?>
      <div class="span4">
        <?php foreach ($shipping_addresses as $shipping_address): ?>
        <div class="well">
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
      </div>
      <?php endif; ?>

      <div class="span8">
        <?= $form['shipping_address'] ?>
      </div>
    </div>
    <?php endif; ?>
  </fieldset>

  <div class="well" style="text-align: center; margin-top: 40px;">
    <?= link_to('cancel this order', '@shopping_cart', array('class' => 'btn')); ?>
    &nbsp - or - &nbsp;
    <button type="submit" class="btn btn-large btn-primary" data-loading-text="Loading payment screen...">Continue to Payment →</button>
  </div>

  <?= $form->renderHiddenFields(); ?>
</form>

<script>
  $(document).ready(function()
  {
    $('.btn-primary').button();
    $('.btn-primary').click(function()
    {
      $(this).button('loading');
    });
  });
</script>
