<?php
  /* @var $form ShippingRatesCollectionForm */

  ice_use_javascript('jquery/chosen.js');
  ice_use_stylesheet('jquery/chosen.css');

  $domestic_rates_form = $form['shipping_domestic'];
  $international_rates_form = $form['shipping_international'];

  echo $form->renderHiddenFields();
?>

<div class="shipping-rates-holder">
  <h2>Shipping rates</h2>

  <?= cq_section_title('Domestic shipping'); ?>
  <br clear="all"/>
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

  <br clear="all"/>

  <?= cq_section_title('International shipping'); ?>
  <br clear="all"/>
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

  <?php if (isset($international_rates_form['do_not_ship_to'])): ?>
  <div class="span-5" style="text-align: right;">
    <?= cq_label_for($international_rates_form, 'do_not_ship_to', __("Do not ship to:")); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
  </div>
  <div class="prepend-1 span-12 last">
    <?= $international_rates_form['do_not_ship_to']->renderError(); ?>
    <?php cq_select_tag($international_rates_form, 'do_not_ship_to', array('class' => 'chosen-do-not-ship')); ?>
  </div>
  <br clear="all"/><br>
  <?php endif; ?>

  <br clear="all"/><br/><br/>
  <div class="span-12" style="text-align: right;">
    <?php cq_button_submit(__('Save Changes'), null, 'float: right;'); ?>
  </div>


  <?php cq_javascript_tag(); ?>
  <script type="text/javascript">
    $(function()
    {
      $(".chosen-do-not-ship").chosen();
    });
  </script>
  <?php cq_end_javascript_tag(); ?>
</div> <!-- .shipping-rates-holder -->