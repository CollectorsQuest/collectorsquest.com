<?php
  /* @var $form CollectibleForSaleEditForm */
  /* @var $form_shipping_us SimpleShippingCollectorCollectibleForCountryForm */
  /* @var $form_shipping_zz SimpleShippingCollectorCollectibleInternationalForm */
?>

<div class="control-group">
  <?= $form['is_ready']->renderLabel('Available for Sale?'); ?>
  <div class="controls switch">
    <?php $enabled = 'on' == $form['is_ready']->getValue(); ?>
    <label class="cb-enable" for="<?=$form['is_ready']->renderId()?>"><span>Yes</span></label>
    <label class="cb-disable selected" for="<?= $form['is_ready']->renderId() ?>">
      <span>No</span>
    </label>
    <div class="visuallyhidden">
      <?= $form['is_ready']->render(array('class' => 'checkbox', 'tabindex'=>'-1')); ?>
    </div>
  </div>
  <br style="clear: both;"/>
  <?= $form['is_ready']->renderError(); ?>
</div>

<div id="form-collectible-for-sale" class="hide">
  <?php if ($collectible->getCollectibleForSale()->hasActiveCredit() ||
     ($sf_user->getSeller()->hasPackageCredits()) && $sf_user->getCollector()->hasPayPalDetails()): ?>

    <div class="control-group">
      <?= $form['price']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token input-prepend">
          <span class="add-on">$</span>
          <span class="required-token">*</span>
          <?php
            echo $form['price']->render(array(
              'class' => 'item-price text-center', 'required' => 'required'
            ));
          ?>
        </div>
        <?= $form['price']->renderError(); ?>
      </div>
    </div>
    <?= $form['tax_country']->renderRow(); ?>
    <?= $form['tax_state']->renderRow(); ?>
    <div class="control-group">
      <?= $form['tax']->renderLabel(); ?>
        <div class="controls">
            <div class="input-prepend">
                <span class="add-on">%</span>
              <?php
              echo $form['tax']->render(array(
                'class' => 'item-price text-center', 'required' => 'required'
              ));
              ?>
            </div>
          <?= $form['tax']->renderError(); ?>
        </div>
    </div>
    <?= $form['condition']->renderRow(); ?>

    <?php if (cqGateKeeper::open('collectible_shipping')): ?>
      <?= $form_shipping_us->renderHiddenFields(); ?>
      <div class="control-group form-inline">
        <label class="control-label" for="">US shipping</label>
        <div class="controls flat-rate-controller">
          <label class="radio">
            <input name="shipping_rates_us[shipping_type]" type="radio"
                   value="free_shipping"
                   id="shipping_rates_us_shipping_type_free_shipping"
                   <?php if ($form_shipping_us->isShippingTypeFreeShipping()) echo 'checked="checked"'; ?>

            />Free shipping
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
          <?php if ($form_shipping_us->isError('flat_rate')): ?>
            <?= $form_shipping_us['flat_rate']->renderError(); ?>
          <?php endif; ?>
        </div>
      </div>

      <?= $form_shipping_zz->renderHiddenFields(); ?>
      <div class="control-group form-inline">
        <label class="control-label" for="">International shipping</label>
        <div class="controls flat-rate-controller">
          <?php if (cqGateKeeper::open('collectible_allow_no_shipping')): ?>
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
          </div>
          <?php if ($form_shipping_zz->isError('flat_rate')): ?>
            <?= $form_shipping_zz['flat_rate']->renderError(); ?>
          <?php endif; ?>
          <br />
          <?php if (cqGateKeeper::open('collectible_allow_no_shipping')): ?><br />
          <label for="shipping_rates_zz_do_not_ship_to">We do not ship to these countries:</label><br />
          <?= $form_shipping_zz['do_not_ship_to']; ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; // if collectible shipping allowed in gatekeeper ?>

  <?php elseif (!$sf_user->getSeller()->hasPackageCredits()): ?>
    <?php
      cq_ad_slot(
        cq_image_tag('headlines/want-to-sell-this-item.png',
          array(
            'width' => '530', 'height' => '71', 'style' => 'display: block; margin: auto',
            'alt' => 'Want to sell this item?'
            )
          ),
        '@seller_packages?return_to='. url_for('mycq_collectible_by_slug', $collectible)
      );
    ?>
    <br/>
  <?php elseif (!$sf_user->getCollector()->hasPayPalDetails()): ?>
    <div class="alert alert-error all-errors">
      You must <?= link_to('setup your store settings', '@mycq_marketplace_settings') ?>
      before you can sell in the Market.
    </div>
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

  <?php if ($sf_params->get('available_for_sale') === 'yes'): ?>
    $('#collectible_for_sale_is_ready').attr('checked', 'checked');
    $('#collectible_for_sale_is_ready').change();
  <?php elseif ($sf_params->get('available_for_sale') === 'no'): ?>
    $('#collectible_for_sale_is_ready').removeAttr('checked');
    $('#collectible_for_sale_is_ready').change();
  <?php endif; ?>

  $('.flat-rate-controller').on('change', 'input[type=radio]', function() {
    var $flat_rate_field = $(this).parents('.controls').find('.flat-rate-field');
    var flat_rate_checked = !!$(this).parents('.controls').find('.flat-rate-checkbox:checked').length;

    if (flat_rate_checked) {
      $flat_rate_field.removeAttr('disabled');
    } else {
      $flat_rate_field.attr('disabled', 'disabled');
    }
  });

  <?php if (cqGateKeeper::open('collectible_allow_no_shipping')): ?>
  $('#shipping_rates_zz_do_not_ship_to').chosen({
    no_results_text: "No countries found for "
  });
  <?php endif; ?>
  var states_cache = {};
  $('#collectible_for_sale_tax_country').change(function()
  {
    var $state = $('#collectible_for_sale_tax_state');
    var $tax = $('#collectible_for_sale_tax');
    var country_code = $(this).val();
    var update_states = function(data)
      {
        var $input = $('#collectible_for_sale_tax_state');
        if (data.length == 0)
        {
          if ($input[0].nodeName.toLowerCase() == 'select')
          {
            var $new_input = $('<input type="text" id="collectible_for_sale_tax_state">')
            $new_input.attr('name', $input.attr('name'));
            $input.replaceWith($new_input);
          }
        }
        else
        {
          var $new_input = $('<select id="collectible_for_sale_tax_state"></select>')
          $new_input.attr('name', $input.attr('name'));
          $.each(data, function(key, value) {
          $new_input.append($("<option></option>")
                          .attr("value", value).text(key));
          });
          $new_input.val($input.val());
          $input.replaceWith($new_input);
        }
      };
    if ($(this).val() == '')
    {
      $state.attr('disabled', 'disabled').closest('.control-group').hide();
      $tax.attr('disabled', 'disabled').closest('.control-group').hide();
    }
    else
    {
      $state.removeAttr('disabled').closest('.control-group').show();
      $tax.removeAttr('disabled').closest('.control-group').show();
      if (country_code in states_cache)
      {
        update_states(states_cache[country_code]);
      }
      else
      {
        $.ajax({
          url: '<?= url_for('@ajax_mycq?section=states&page=lookup'); ?>',
            type: 'GET',
              data: {
                c: country_code
                },
              dataType: 'json',
              success: function(responce)
                {
                  states_cache[country_code] = responce;
                  update_states(states_cache[country_code]);
                }
        });
      }
    }
  }).change();
});
</script>
