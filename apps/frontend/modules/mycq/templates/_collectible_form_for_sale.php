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
  <?php if ($collectible->getCollectibleForSale()->hasActiveCredit() || $sf_user->getSeller()->hasPackageCredits()): ?>

    <div class="control-group">
      <?= $form['price']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token input-prepend">
          <span class="add-on">$</span>
          <span class="required-token">*</span>
          <?php
            echo $form['price']->render(array(
              'class' => 'span5 text-center', 'required' => 'required',
              'style' => 'margin-left: -4px; font-size: 120%; padding-top: 8px;'
            ));
          ?>
        </div>
        <?= $form['price']->renderError(); ?>
      </div>
    </div>
    <?= $form['condition']->renderRow(); ?>

    <?php if (IceGateKeeper::open('collectible_shipping')): ?>
      <?= $form_shipping_us->renderHiddenFields(); ?>
      <?= $form_shipping_us->renderAllErrors(); ?>
      <div class="control-group form-inline">
        <label class="control-label" for="">US shipping</label>
        <div class="controls flat-rate-controller">
          <label class="radio">
            <input name="shipping_rates_us[shipping_type]" type="radio"
                   value="free_shipping"
                   id="shipping_rates_us_shipping_type_free_shipping"
                   <?php if ($form_shipping_us->isShippingTypeFreeShipping()) echo 'checked="checked"'; ?>

            />Free Shipping
          </label><br />
          <label class="radio">
            <input name="shipping_rates_us[shipping_type]"
                   type="radio"
                   value="flat_rate"
                   class="flat-rate-checkbox"
                   id="shipping_rates_us_shipping_type_flat_rate"
                   <?php if (!$form_shipping_us->isShippingTypeFreeShipping()) echo 'checked="checked"'; ?>
            />Flat rate
          </label>
          <div class="input-prepend spacer-left-15 spacer-top-5">
            <span class="add-on">$</span><?= $form_shipping_us['flat_rate']->render(array(
              'class' => 'input-small flat-rate-field')); ?>
          </div>
        </div>
      </div>

      <?= $form_shipping_zz->renderHiddenFields(); ?>
      <?= $form_shipping_zz->renderAllErrors(); ?>
      <div class="control-group form-inline">
        <label class="control-label" for="">International shipping</label>
        <div class="controls flat-rate-controller">
          <?php if (IceGateKeeper::open('collectible_allow_no_shipping')): ?>
          <label class="radio">
            <input name="shipping_rates_zz[shipping_type]" type="radio"
                   value="no_shipping"
                   id="shipping_rates_zz_shipping_type_no_shipping"
                   <?php if ($form_shipping_zz->isShippingTypeNoShipping()) echo 'checked="checked"'; ?>
            />Not available
          </label><br />
          <?php endif; ?>
          <label class="radio">
            <input name="shipping_rates_zz[shipping_type]" type="radio"
                   value="free_shipping"
                   id="shipping_rates_zz_shipping_type_free_shipping"
                   <?php if ($form_shipping_zz->isShippingTypeFreeShipping()) echo 'checked="checked"'; ?>
            />Free shipping
          </label><br />
          <label class="radio">
            <input name="shipping_rates_zz[shipping_type]"
                   type="radio"
                   value="flat_rate"
                   class="flat-rate-checkbox"
                   id="shipping_rates_zz_shipping_type_flat_rate"
                   <?php if (!($form_shipping_zz->isShippingTypeNoShipping() || $form_shipping_zz->isShippingTypeFreeShipping())) echo 'checked="checked"'; ?>
            />Flat rate
          </label>
          <div class="input-prepend spacer-left-15 spacer-top-5">
            <span class="add-on">$</span><?= $form_shipping_zz['flat_rate']->render(array(
              'class' => 'input-small flat-rate-field')); ?>
          </div><br />
          <?php if (IceGateKeeper::open('collectible_allow_no_shipping')): ?><br />
          <label for="shipping_rates_zz_do_not_ship_to">We do not ship to these countries:</label><br />
          <?= $form_shipping_zz['do_not_ship_to']; ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; // if collectible shipping allowed in gatekeeper ?>

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
  'use strict';

  $('#collectible_for_sale_is_ready').change(function()
  {
    var checked = $(this).attr('checked') == 'checked';
    $('#form-collectible-for-sale').toggleClass(
      'hide', !checked
    );
    $('.cb-enable').toggleClass('selected', checked);
    $('.cb-disable').toggleClass('selected', !checked);
  }).change();

  $('.flat-rate-controller').on('change', 'input[type=radio]', function() {
    var $flat_rate_field = $(this).parents('.controls').find('.flat-rate-field');
    var flat_rate_checked = !!$(this).parents('.controls').find('.flat-rate-checkbox:checked').length;

    if (flat_rate_checked) {
      $flat_rate_field.removeAttr('disabled');
    } else {
      $flat_rate_field.attr('disabled', 'disabled');
    }
  })

  <?php if (IceGateKeeper::open('collectible_allow_no_shipping')): ?>
  $('#shipping_rates_zz_do_not_ship_to').chosen({
    no_results_text: "No countries found for "
  });
  <?php endif; ?>
});
</script>
