<?php
  /* @var $form ShoppingOrderShippingForm */
?>
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
    <div class="control-group span8 spacer-left-reset <?= $form->isError('buyer_email') ? 'error' : '' ?>">
      <?= $form['buyer_email']->renderLabel(null, array('class' => 'control-label')); ?>
      <div class="controls">
        <div class="input-prepend with-required-token">
          <span class="add-on"><i class="icon-envelope"></i></span>
          <span class="required-token">*</span>
          <?= $form['buyer_email']->render(array('class' => 'span4', 'style' => 'margin-left: -4px;')) ?>
        </div>
        <?= $form['buyer_email']->renderError() ?>
      </div>
    </div>
    <div class="control-group span8 spacer-left-reset <?= $form->isError('buyer_phone') ? 'error' : '' ?>">
      <?= $form['buyer_phone']->renderLabel(null, array('class' => 'control-label')); ?>
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on"><i class="icon-phone"></i></span>
          <?= $form['buyer_phone']->render(array('class' => 'span4', 'style' => 'margin-left: -4px;')) ?>
        </div>
        <?= $form['buyer_phone']->renderError() ?>
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

    var states_cache = {};
    var $country_select = $('#shopping_order_shipping_address_country_iso3166');
    $country_select.change(function()
    {

      var $state = $('#shopping_order_shipping_address_state_region');
      var country_code = $(this).val();
      var update_states = function(data)
      {
        if (data.length == 0)
        {
          if ($state[0].nodeName.toLowerCase() == 'select')
          {
            var $new_input = $('<input type="text" />')
            $new_input.attr('name', $state.attr('name'));
            $new_input.attr('id', $state.attr('id'));
            $state.replaceWith($new_input);
          }
        }
        else
        {
          var $new_input = $('<select><option value="0"></option></select>')
          $new_input.attr('name', $state.attr('name'));
          $new_input.attr('id', $state.attr('id'));
          $.each(data, function(key, value) {
            $new_input.append($("<option></option>")
                .attr("value", key).text(value));
          });
          $new_input.val($state.val());
          $state.replaceWith($new_input);
        }

        $state.hideLoading();
        if ($new_input)
        {
          $new_input.animate( { backgroundColor: "#ffffcc" }, 1)
                    .animate( { backgroundColor: "#ffffff" }, 2500);
        }
        else
        {
          $state.animate( { backgroundColor: "#ffffcc" }, 1)
                .animate( { backgroundColor: "#ffffff" }, 2500);
        }

      };
      $state.find('option').remove();
      if (country_code in states_cache)
      {
        update_states(states_cache[country_code]);
      }
      else
      {
        $state.showLoading();
        $.ajax({
          url: '<?= url_for('@ajax?section=states&page=lookup'); ?>',
          type: 'GET',
          data: {
            c: country_code
          },
          dataType: 'json',
          success: function(data)
          {
            states_cache[country_code] = data;
            update_states(states_cache[$country_select.val()]);
          }
        });
      }

    });

  <?php if (0 != $shopping_order->getCollectibleForSale()->getTaxPercentage()): ?>
    // Update right bar when need include or exclude tax to total amount
    $('#shopping_order_shipping_address_country_iso3166, #shopping_order_shipping_address_state_region')
        .live('change', function()
        {
          //Hide or show tax information
          if ($('#shopping_order_shipping_address_country_iso3166').val() == '<?=
            $shopping_order->getCollectibleForSale()->getTaxCountry(); ?>'
            <?php if ($shopping_order->getCollectibleForSale()->getTaxState()): ?>
              && $('#shopping_order_shipping_address_state_region').val() == '<?=
            $shopping_order->getCollectibleForSale()->getTaxState(); ?>'
            <?php endif ?>
              )
          {
            $('.with_tax').removeClass('hide');
            $('.no_tax').addClass('hide');
          }
          else
          {
            $('.with_tax').addClass('hide');
            $('.no_tax').removeClass('hide');
          }
        });
    $('#shopping_order_shipping_address_state_region').change();
    <?php endif; ?>
  });
</script>
