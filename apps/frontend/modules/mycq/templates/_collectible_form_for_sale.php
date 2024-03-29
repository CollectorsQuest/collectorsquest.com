<?php
  /* @var $sf_user cqFrontendUser */
  /* @var $form CollectibleForSaleEditForm|sfFormField[] */
  /* @var $form_shipping_us SimpleShippingCollectorCollectibleForCountryForm|sfFormField[] */
  /* @var $form_shipping_zz SimpleShippingCollectorCollectibleInternationalForm|sfFormField[] */
  /* @var $sf_params sfParameterHolder */
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

    <?php if ($sf_user->isAdmin() && !$collectible->getCollectibleForSale()->isForSale() && !$collectible->getCollectibleForSale()->getIsSold() && $collectible->getCollectibleForSale()->hasActiveCredit()): ?>
    <div style="float: left; margin-left: 20px; padding-top: 7px;">
      <a href="<?= url_for('mycq_collectible_by_slug', array(
        'sf_subject' => $collectible,
        'cmd' => 'markAsSold',
        'encrypt' => 1
      )) ?>">
        <i class="icon icon-money"></i>&nbsp;&nbsp;Mark as Sold
      </a>
    </div>
    <?php endif; ?>
  </div>
  <br style="clear: both;"/>
  <?= $form['is_ready']->renderError(); ?>
</div>

<div id="form-collectible-for-sale" class="hide">
  <?php if (
     $collectible->getCollectibleForSale()->hasActiveCredit() ||
     ($sf_user->getSeller() && $sf_user->getSeller()->hasPackageCredits()) &&
     $sf_user->getCollector()->hasPayPalDetails()
    ): ?>

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
    <?= $form['condition']->renderRow(); ?>

    <?= $form_shipping_us->renderHiddenFields(); ?>
    <div class="control-group form-inline">
      <label class="control-label">US shipping</label>
      <div class="controls flat-rate-controller">
        <label class="radio">
          <input name="shipping_rates_us[shipping_type]" type="radio"
                 value="<?= ShippingReferencePeer::SHIPPING_TYPE_LOCAL_PICKUP_ONLY; ?>"
                 id="shipping_rates_us_shipping_type_local_pickup_only"
                 <?= ShippingReferencePeer::SHIPPING_TYPE_LOCAL_PICKUP_ONLY == $form_shipping_us->getCurrentShippingType() ? 'checked="checked"' : ''; ?>
          />Local Pickup Only
        </label><br />
        <label class="radio">
          <input name="shipping_rates_us[shipping_type]" type="radio"
                 value="<?= SimpleShippingCollectorCollectibleForCountryForm::SHIPPING_TYPE_FREE_SHIPPING; ?>"
                 id="shipping_rates_us_shipping_type_free_shipping"
                 <?= SimpleShippingCollectorCollectibleForCountryForm::SHIPPING_TYPE_FREE_SHIPPING == $form_shipping_us->getCurrentShippingType() ? 'checked="checked"' : ''; ?>
          />Free shipping
        </label><br />
        <label class="radio">
          <input name="shipping_rates_us[shipping_type]" type="radio"
                 value="<?= ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE; ?>"
                 class="flat-rate-checkbox"
                 id="shipping_rates_us_shipping_type_flat_rate"
                 <?= ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE == $form_shipping_us->getCurrentShippingType() ? 'checked="checked"' : ''; ?>
          />Flat rate
        </label>
        <div class="input-prepend spacer-left-15 spacer-top-5">
          <span class="add-on">$</span>
          <?= $form_shipping_us['flat_rate']->render(array(
            'class' => 'input-small flat-rate-field'));
          ?>
        </div>
        <?php if ($form_shipping_us->isError('flat_rate')): ?>
          <?= $form_shipping_us['flat_rate']->renderError(); ?>
        <?php endif; ?>
        <?php if ($form_shipping_us->isError('shipping_type')): ?>
          <?= $form_shipping_us['shipping_type']->renderError(); ?>
        <?php endif; ?>
      </div>
    </div>

    <?= $form_shipping_zz->renderHiddenFields(); ?>
    <div class="control-group form-inline">
      <label class="control-label">International shipping</label>
      <div class="controls flat-rate-controller">
        <label class="radio">
          <input name="shipping_rates_zz[shipping_type]" type="radio"
                 value="<?= ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING; ?>"
                 id="shipping_rates_zz_shipping_type_no_shipping"
                 <?= ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $form_shipping_zz->getCurrentShippingType() ? 'checked="checked"' : ''; ?>
          />Not available
        </label><br />
        <label class="radio">
          <input name="shipping_rates_zz[shipping_type]" type="radio"
                 value="<?= SimpleShippingCollectorCollectibleInternationalForm::SHIPPING_TYPE_FREE_SHIPPING; ?>"
                 id="shipping_rates_zz_shipping_type_free_shipping"
                 <?= SimpleShippingCollectorCollectibleInternationalForm::SHIPPING_TYPE_FREE_SHIPPING == $form_shipping_zz->getCurrentShippingType() ? 'checked="checked"' : ''; ?>
          />Free shipping
        </label><br />
        <label class="radio">
          <input name="shipping_rates_zz[shipping_type]" type="radio"
                 value="<?= ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE; ?>"
                 class="flat-rate-checkbox"
                 id="shipping_rates_zz_shipping_type_flat_rate"
                 <?= ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE == $form_shipping_zz->getCurrentShippingType() ? 'checked="checked"' : ''; ?>
          />Flat rate
        </label>
        <div class="input-prepend spacer-left-15 spacer-top-5">
          <span class="add-on">$</span><?= $form_shipping_zz['flat_rate']->render(array(
            'class' => 'input-small flat-rate-field')); ?>
        </div>
        <?php if ($form_shipping_zz->isError('flat_rate')): ?>
          <?= $form_shipping_zz['flat_rate']->renderError(); ?>
        <?php endif; ?>
        <?php if ($form_shipping_zz->isError('shipping_type')): ?>
          <?= $form_shipping_zz['shipping_type']->renderError(); ?>
        <?php endif; ?>
        <br /><br />
        <label for="shipping_rates_zz_do_not_ship_to">We do not ship to these countries:</label><br />
        <?= $form_shipping_zz['do_not_ship_to']; ?>
      </div>
    </div>

    <?php if (cqGateKeeper::open('shopping_collectible_tax')): ?>
      <?php cq_section_title('Tax Information <small style="color: grey;">(optional)</small>'); ?>
      <br/>

      <?= $form['tax_country']->renderRow(array(), 'Country'); ?>
      <?= $form['tax_state']->renderRow(array(), 'State / Province'); ?>
      <div class="control-group">
        <?= $form['tax']->renderLabel('Percentage'); ?>
        <div class="controls">
          <div class="input-append">
            <?php
              echo $form['tax']->render(array(
                'class' => 'item-price text-center', 'required' => 'required'
              ));
            ?>
            <span class="add-on">%</span>
          </div>
          <?= $form['tax']->renderError(); ?>
        </div>
      </div>
    <?php endif; ?>

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

  var $is_ready = $('#collectible_for_sale_is_ready');

  $is_ready.change(function()
  {
    var checked = $(this).attr('checked') == 'checked';
    $('#form-collectible-for-sale').toggleClass(
      'hide', !checked
    );
    $('.cb-enable').toggleClass('selected', checked);
    $('.cb-disable').toggleClass('selected', !checked);
  }).change();

  <?php if ($sf_params->get('available_for_sale') === 'yes'): ?>
    $is_ready.attr('checked', 'checked');
    $is_ready.change();
  <?php elseif ($sf_params->get('available_for_sale') === 'no'): ?>
    $is_ready.removeAttr('checked');
    $is_ready.change();
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

  $('#shipping_rates_zz_do_not_ship_to').chosen({
    no_results_text: "No countries found for "
  });
  $('#collectible_for_sale_tax_country').change(function()
  {
    if ($(this).val() == '')
    {
      $('#collectible_for_sale_tax_state')
          .attr('disabled', 'disabled').closest('.control-group').addClass('hide');
      $('#collectible_for_sale_tax')
          .attr('disabled', 'disabled').closest('.control-group').addClass('hide');
    }
    else
    {
      $('#collectible_for_sale_tax_state')
          .removeAttr('disabled').closest('.control-group').removeClass('hide');
      $('#collectible_for_sale_tax')
          .removeAttr('disabled').closest('.control-group').removeClass('hide');
    }
  }).change();
<?php // Restrict to "United States" only
/*  var states_cache = {};
  $('#collectible_for_sale_tax_country').change(function()
  {
    var $state = $('#collectible_for_sale_tax_state');
    var $tax = $('#collectible_for_sale_tax');
    var country_code = $(this).val();
    var update_states = function(data)
    {
      if (data.length == 0)
      {
        if ($state[0].nodeName.toLowerCase() == 'select')
        {
          var $new_input = $('<input type="text">')
          $new_input.attr('name', $state.attr('name'));
          $new_input.attr('id', $state.attr('id'));
          $state.replaceWith($new_input);
        }
      }
      else
      {
        var $new_input = $('<select></select>')
        $new_input.attr('name', $state.attr('name'));
        $new_input.attr('id', $state.attr('id'));
        $.each(data, function(key, value) {
          $new_input.append($("<option></option>")
              .attr("value", key).text(value));
        });
        $new_input.val($state.val());
        $state.replaceWith($new_input);
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
          url: '<?= url_for('@ajax?section=states&page=lookup'); ?>',
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
  }).change(); */
?>
});
</script>
