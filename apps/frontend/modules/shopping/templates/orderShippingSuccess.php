<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping & Handling'), __('Payment'), __('Review')) , 'active' => 1)
  );
?>
<br/><br/>

<form action="<?= url_for('@shopping_order_shipping?uuid='. $shopping_order->getUuid()); ?>"
      method="post" class="form-horizontal" novalidate="novalidate">

  <fieldset>
    <legend>Contact Details</legend>
    <div class="control-group">
      <?= $form['buyer_email']->renderLabel(null, array('class' => 'control-label')); ?>
      <div class="controls">
        <div class="input-prepend with-required-token">
          <span class="add-on"><i class="icon-envelope"></i></span>
          <span class="required-token">*</span>
          <?= $form['buyer_email']->render(array('class' => 'span4', 'style' => 'margin-left: -4px;')) ?>
          <?= $form['buyer_email']->renderError() ?>
        </div>
      </div>
    </div>
    <div class="control-group">
      <?= $form['buyer_phone']->renderLabel(null, array('class' => 'control-label')); ?>
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on"><i class="icon-phone"></i></span>
          <?= $form['buyer_phone']->render(array('class' => 'span4', 'style' => 'margin-left: -4px;')) ?>
          <?= $form['buyer_phone']->renderError() ?>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>
      Shipping Address
    </legend>

    <br/>
    <div class="row-fluid">
      <?php if (count($shipping_addresses) > 0): ?>
      <div class="span4 shipping-addresses">
        <?php foreach ($shipping_addresses as $shipping_address): ?>
        <div class="well <?= $sf_params->get('address_id') == $shipping_address->getId() ? 'highlight' : '' ?>">
          <?= $shipping_address->getFullName(); ?><br/>
          <?= $shipping_address->getAddressLine1(); ?>
          <p>
            <?= $shipping_address->getCity(); ?>,
            <?= $shipping_address->getStateRegion(); ?>
            <?= $shipping_address->getZipPostcode(); ?>
          </p>
          <p style="font-weight: bold;"><?= $shipping_address->getCountryName(); ?></p>
          <a href="<?= url_for('@shopping_order_shipping?uuid='. $shopping_order->getUuid() .'&address_id='. $shipping_address->getId()); ?>"
             class="btn" style="padding: 4px 10px;">
            Ship to this address
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="span8">
        <h4 class="text-center spacer-bottom-25"><?= null ===$sf_params->get('address_id')
          ? 'Enter your shipping information here'
          : 'Edit your shipping information if necessary'
        ?></h4>
        <?= $form['shipping_address'] ?>
      </div>
    </div>
  </fieldset>

  <div class="well" style="text-align: center; margin-top: 40px;">
    <button type="submit" class="btn btn-large btn-primary spacer-right-15"
            data-loading-text="Loading payment screen..." formnovalidate="formnovalidate">
      Continue to Payment
    </button>

    <?= link_to('Cancel', '@shopping_cart', array('class' => 'btn')); ?>
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
