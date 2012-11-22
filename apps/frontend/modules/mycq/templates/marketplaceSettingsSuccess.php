<?php
  /* @var $collector Collector */
  /* @var $form CollectorEditForm */
  foreach ($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute(
      'class', $form_field->getAttribute('class') . ' input-xxlarge'
    );
  }

  SmartMenu::setSelected('mycq_marketplace_tabs', 'settings');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?= form_tag('@mycq_marketplace_settings', array('class' => 'form-horizontal', 'multipart' => true)); ?>
        <?= $form->renderHiddenFields(); ?>

        <?php if ($form->hasGlobalErrors()): ?>
          <?= $form->renderAllErrors(); ?>
        <?php else: ?>
          <div class="alert alert-block">
            <h2 class="alert-heading">You must complete all information on this page before you can begin selling.</h2>
            <ul>
              <li>Collectors Quest uses PayPal<sup>®</sup> to process all payments made to sellers on our site.</li>
              <li>
                If you don't have a PayPal<sup>®</sup> account, make sure to
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_registration-run" target="_blank">
                  <strong>sign up now!</strong>
                </a>
              </li>
              <li>
                You need to enter your <strong>full name</strong> and <strong>email address</strong>
                exactly as it appears on your PayPal account so that we can verify your information.
              </li>
            </ul>
          </div>
        <?php endif; ?>

        <?php cq_section_title('PayPal Account'); ?>

        <fieldset class="form-container-center spacer-top-20">
          <?= $form['seller_settings_paypal_email']->renderRow(); ?>
          <?= $form['seller_settings_paypal_fname']->renderRow(); ?>
          <?= $form['seller_settings_paypal_lname']->renderRow(); ?>
          <?php // $form['seller_settings_phone_number']->renderRow(); ?>
        </fieldset>


        <?php cq_section_title('Store Information'); ?>

        <fieldset class="form-container-center spacer-top-20">
          <?= $form['seller_settings_store_name']->renderRow() ?>
          <?= $form['seller_settings_store_header_image']->renderRow(array(), null, 'A image to be used as store header that will be resized to 620x67 pixels') ?>
          <?= $form['seller_settings_store_title']->renderRow() ?>
          <?= $form['seller_settings_refunds']->renderRow() ?>
          <?= $form['seller_settings_shipping']->renderRow() ?>
        </fieldset>

        <?php cq_section_title('Tax Details'); ?>

          <fieldset class="form-container-center spacer-top-20">
            <?= $form['seller_settings_tax_country']->renderRow() ?>
            <?= $form['seller_settings_tax_state']->renderRow() ?>
              <div class="control-group">
                <?= $form['seller_settings_tax_percentage']->renderLabel(); ?>
                  <div class="controls">
                      <div class="input-prepend">
                          <span class="add-on">%</span>
                        <?php
                        echo $form['seller_settings_tax_percentage']->render(array(
                          'class' => 'input-small text-right'
                        ));
                        ?>
                      </div>
                    <?= $form['seller_settings_tax_percentage']->renderError(); ?>
                  </div>
              </div>
          </fieldset>

        <?php cq_section_title('Shipping & Handling') ?>

        <?= $form_shipping_us->renderHiddenFields(); ?>
        <fieldset class="form-container-center spacer-top-20">
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
        </fieldset>

        <fieldset class="form-container-center spacer-top-20">
          <?= $form_shipping_zz->renderHiddenFields(); ?>
          <div class="control-group form-inline">
            <label class="control-label" for="">International shipping</label>
            <div class="controls flat-rate-controller">
              <?php if (cqGateKeeper::open('collectible_allow_no_shipping')): ?>
              <label class="radio">
                <input name="shipping_rates_zz[shipping_type]" type="radio"
                       value="no_shipping"
                       id="shipping_rates_zz_shipping_type_no_shipping"
                       <?php if ($form_shipping_zz->getDefault('shipping_type') == ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING) echo 'checked="checked"'; ?>
                />Not available
              </label><br />
              <?php endif; ?>
              <label class="radio">
                <input name="shipping_rates_zz[shipping_type]" type="radio"
                       value="free_shipping"
                       id="shipping_rates_zz_shipping_type_free_shipping"
                       <?php if ($form_shipping_zz->getDefault('shipping_type') == SimpleShippingCollectorCollectibleForCountryForm::SHIPPING_TYPE_FREE) echo 'checked="checked"'; ?>
                />Free shipping
              </label><br />
              <label class="radio">
                <input name="shipping_rates_zz[shipping_type]"
                       type="radio"
                       value="flat_rate"
                       class="flat-rate-checkbox"
                       id="shipping_rates_zz_shipping_type_flat_rate"
                       <?php if ($form_shipping_zz->getDefault('shipping_type') == ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE) echo 'checked="checked"'; ?>
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
              <?= $form_shipping_zz['do_not_ship_to']->render(array('class'=>'input-xxlarge')); ?>
              <?php endif; ?>
            </div>
          </div>
        </fieldset>

        <div class="form-actions">
          <input type="submit" class="btn btn-primary spacer-right-15"
                 name="save[and_add_new_items]" value="Save & Add Items for Sale" />
          <input type="submit" class="btn" value="Save Changes"
                 name="save[and_stay_where_you_are]" />
        </div>
        <?php echo '</form>'; ?>

      </div>
      <!-- .tab-content-inner.spacer -->
    </div>
    <!-- .tab-pane.active -->
  </div>
  <!-- .tab-content -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.flat-rate-controller').on('change', 'input[type=radio]', function () {
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

        var states_cache = {};
        $('#collector_seller_settings_tax_country').change(function()
        {
            var $state = $('#collector_seller_settings_tax_state');
            var $tax = $('#collector_seller_settings_tax_percentage');
            var country_code = $(this).val();
            var update_states = function(data)
            {
                var $input = $('#collector_seller_settings_tax_state');
                if (data.length == 0)
                {
                    if ($input[0].nodeName.toLowerCase() == 'select')
                    {
                        var $new_input = $('<input type="text" id="collector_seller_settings_tax_state">')
                        $new_input.attr('name', $input.attr('name'));
                        $input.replaceWith($new_input);
                    }
                }
                else
                {
                    var $new_input = $('<select id="collector_seller_settings_tax_state"></select>')
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
