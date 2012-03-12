<?php
  /* @var $form ShippingRatesCollectionForm */

  echo $form->renderHiddenFields();

  $domestic_rates_form = $form['shipping_domestic'];
  $international_rates_form = $form['shipping_international'];

?>

<h3>Domestic shipping</h3>
<div class="span-5" style="text-align: right;">
  <?= cq_label_for($domestic_rates_form, 'calculation_type', __("Postage:")); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= $domestic_rates_form['calculation_type']->renderError(); ?>
  <?php cq_select_tag($domestic_rates_form, 'calculation_type'); ?>
</div>
<br clear="all"/><br>

<?php include_partial('embedded_shipping_form', array('form' => $domestic_rates_form, 'form_has_errors' => $form->hasErrors())); ?>

<br clear="all"/><br>
<hr style="border: 1px dotted grey; border-bottom: 0;" />

<h3>International shipping</h3>
<div class="span-5" style="text-align: right;">
  <?= cq_label_for($international_rates_form, 'calculation_type', __("Postage:")); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= $international_rates_form['calculation_type']->renderError(); ?>
  <?php cq_select_tag($international_rates_form, 'calculation_type'); ?>
</div>
<br clear="all"/><br>

<?php include_partial('embedded_shipping_form', array('form' => $international_rates_form, 'form_has_errors' => $form->hasErrors())); ?>

<br clear="all"/><br/><br/>
<div class="span-12" style="text-align: right;">
  <?php cq_button_submit(__('Save Changes'), null, 'float: right;'); ?>
</div>