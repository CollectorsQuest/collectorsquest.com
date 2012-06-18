<?php
/**
 * @var $form CollectibleForSaleEditForm
 * @var $form_shipping_us SimpleShippingCollectorCollectibleForCountryForm
 */
?>

<div class="control-group">
  <?= $form['is_ready']->renderLabel('Available for Sale?'); ?>
  <div class="controls switch">
    <?php $enabled = 'on' == $form['is_ready']->getValue(); ?>
    <label class="cb-enable" for="<?=$form['is_ready']->renderId()?>"><span>Yes</span></label>
    <label class="cb-disable selected" for="<?= $form['is_ready']->renderId() ?>">
      <span>No</span>
    </label>
    <?= $form['is_ready']->render(array('class' => 'checkbox hide')); ?>
  </div>
  <br style="clear: both;"/>
  <?= $form['is_ready']->renderError(); ?>
</div>

<div id="form-collectible-for-sale" class="hide">
  <?php if ($sf_user->getSeller()->hasPackageCredits()): ?>

    <div class="control-group">
      <?= $form['price']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?php
            echo $form['price']->render(array(
              'class' => 'span2 text-center help-inline', 'required'=>'required'
            ));
          ?>
          <?= $form['price_currency']->render(array('class' => 'span2 help-inline')); ?>
        </div>
        <?= $form['price']->renderError(); ?>
      </div>
    </div>
    <?= $form['condition']->renderRow(); ?>

    <?= $form_shipping_us->renderHiddenFields(); ?>
    <?= $form_shipping_us->renderAllErrors(); ?>
    <div class="control-group form-inline">
      <label class="control-label" for="">Domestic shipping</label>
      <div class="controls">
        <label class="radio">
          <input name="shipping_rates_us[shipping_type]" type="radio"
                 value="free_shipping"
                 id="shipping_rates_us_shipping_type_free_shipping"
                 <?php if (!$form_shipping_us->isShippingTypeFlatRate()) echo 'checked="checked"'; ?>

          />Free Shipping
        </label><br />
        <label class="radio">
          <input name="shipping_rates_us[shipping_type]"
                 type="radio"
                 value="flat_rate"
                 id="shipping_rates_us_shipping_type_flat_rate"
                 <?php if ($form_shipping_us->isShippingTypeFlatRate()) echo 'checked="checked"'; ?>
          />Flat rate
        </label>
        <div class="input-prepend spacer-left-15 spacer-top-5">
          <span class="add-on">$</span><?= $form_shipping_us['flat_rate']->render(array(
            'class' => 'input-small')); ?>
        </div>
      </div>
    </div>

  <?php else: ?>
    <center>
      <?php
        echo link_to(
          image_tag('banners/want-to-sell-this-item.png'),
          '@seller_packages'
        );
      ?>
    </center>
    <br/>
  <?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  $('#collectible_for_sale_is_ready').change(function()
  {
    var checked = $(this).attr('checked') == 'checked';
    $('#form-collectible-for-sale').toggleClass(
      'hide', !checked
    );
    $('.cb-enable').toggleClass('selected', checked);
    $('.cb-disable').toggleClass('selected', !checked);
  }).change();
});
</script>
