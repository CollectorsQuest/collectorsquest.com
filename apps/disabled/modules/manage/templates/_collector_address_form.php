<?php
  /* @var $form FrontendCollectorAddressForm */
?>

<?php echo $form->renderHiddenFields(); ?>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'full_name'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'full_name', array('width' => 400)); ?>
  <?= $form['full_name']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'address_line_1'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'address_line_1', array('width' => 400)); ?>
  <?= $form['address_line_1']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'address_line_2'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'address_line_2', array('width' => 400)); ?>
  <?= $form['address_line_2']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'city'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'city', array('width' => 400)); ?>
  <?= $form['city']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'state_region'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'state_region', array('width' => 400)); ?>
  <?= $form['state_region']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'zip_postcode'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'zip_postcode', array('width' => 400)); ?>
  <?= $form['zip_postcode']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;"> <?= cq_label_for($form, 'country_iso3166'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_select_tag($form, 'country_iso3166', array('width' => 400)); ?>
  <?= $form['country_iso3166']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'phone'); ?>:
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'phone', array('width' => 400)); ?>
  <?= $form['phone']->renderError(); ?>
</div>
<br clear="all"/><br />

<input type="submit" value="Save & Continue" />