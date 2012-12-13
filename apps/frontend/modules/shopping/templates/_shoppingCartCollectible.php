<?php
/**
 * @var $shopping_cart_collectible ShoppingCartCollectible
 * @var $form ShoppingCartCollectibleCheckoutForm
 */
?>

<div class="row-fluid">
  <div class="row-fluid line-under-title">
    <div class="span11"><!-- Title-->
      <p class="seller-link">Purchase from <?= link_to_collector($shopping_cart_collectible->getCollector(), 'text'); ?></p>
    </div><!-- /Title-->
    <div class="span1"><!-- Remove from cart-->
      <div class="pull-right">
        <?= link_to('<i class="icon-remove"></i>', '@shopping_cart_remove?id='. $shopping_cart_collectible->getCollectibleId(),
          array('class' => 'close-button', 'rel' => 'tooltip', 'title' => 'Remove Item'
        )); ?>
      </div>
    </div><!-- /Remove from cart-->
  </div>

  <form action="<?= url_for('@shopping_cart_checkout'); ?>" method="post">
    <div class="row-fluid">
      <div class="span8">
        <div class="row-fluid">
          <div class="span3 sc-thumb"><!--Image-->
            <?= image_tag_collectible($shopping_cart_collectible->getCollectible(), '75x75'); ?>
          </div>
          <div class="span9"><!--Info-->
            <?= link_to_collectible($shopping_cart_collectible->getCollectible(), 'text', array('class' => 'title-item')); ?>
            <span class="label-condition">
              <?= __('%condition% condition', array('%condition%' => $shopping_cart_collectible->getCondition())); ?>
            </span>
            <p><?= $shopping_cart_collectible->getDescription(); ?></p>
          </div>
        </div>
        <div><!--Note on the order-->
          <?php
          echo $form['note_to_seller']->render(array(
            'class' => 'simple-textarea',
            'placeholder' => 'If you have any special requests, please specify them here...'
          ));
          ?>
        </div>
      </div><!-- /span8 -->
      <div class="span4">
        <table class="spacer-bottom-reset"><!--Start cost table-->
          <tr>
            <td colspan="2">
              Ship to:<br/>
              <?= $form['country_iso3166']->render(array(
                  'style' => 'width: 100%;',
                  'class' => 'collectible-country',
                  'data-collectible-id' => $shopping_cart_collectible->getCollectibleId(),
              )); ?>
            </td>
          </tr>
          <?php if (isset($form['state_region'])): ?>
            <tr>
              <td colspan="2">
                State: <small>(for tax calculation)</small><br/>
                <?= $form['state_region']->render(array(
                  'class' => 'collectible-state',
                  'data-collectible-id' => $shopping_cart_collectible->getCollectibleId(),
                )); ?>
              </td>
            </tr>
          <?php endif; ?>
          <tr>
            <td>Price:</td>
            <td class="text-right">
              <?= money_format('%.2n', (float) $shopping_cart_collectible->getPriceAmount()); ?>
              <small><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <?php if ($promotion = $shopping_cart_collectible->getSellerPromotion()): ?>
            <tr>
              <td><?= $promotion->getPromotionName() ?></td>
              <td class="text-right">
                <?php if (0 != (int) $promotion->getAmount()): ?>
                  - <?= money_format('%.2n', (float) $shopping_cart_collectible->getPromotionAmount()); ?>
                  <small><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
                <?php endif ?>
              </td>
            </tr>
          <?php endif; ?>
          <?php if (0 != (int) $shopping_cart_collectible->getTaxAmount()): ?>
            <tr>
              <td>Tax (<?= $shopping_cart_collectible->getCollectibleForSale()->getTaxPercentage() ?>%):</td>
              <td class="text-right">
                <?= money_format('%.2n', (float) $shopping_cart_collectible->getTaxAmount()); ?>
                <small><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
              </td>
            </tr>
          <?php endif; ?>
          <tr>
            <td>Shipping:</td>
            <td class="text-right">
              <?php if ($cannot_ship): ?>
                <span class="red">Cannot be shipped to <?= $country ?>!</span>
              <?php elseif ($shopping_cart_collectible->getShippingFeeAmount() > 0): ?>
                <?= money_format('%.2n', (float) $shopping_cart_collectible->getShippingFeeAmount()); ?>
                <small><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
              <?php else: ?>
                Free
              <?php endif; ?>
            </td>
          </tr>
          <tr class="rainbow-dash">
            <td><strong>Total:</strong></td>
            <td class="text-right">
              <strong><?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?></strong>
              <small class="text-bold"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="seller-promo">
              <div class="input-append">
                <?php echo $form['promotion_code']->render(array('class' => 'input-small', 'placeholder'=>'Discount code')); ?>
                <button type="button" data-collectible-id="<?=$shopping_cart_collectible->getCollectibleId() ?>"
                        class="btn apply-promo">Apply</button>
              </div>
              <div class="red"></div>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="text-center">
              <button type="submit" name="Checkout" <?= (!$cannot_ship ?: 'disabled="disabled"'); ?>
                      class="btn btn-primary" value="Checkout" style="width: 100%;">
                Proceed to Checkout
              </button>
            </td>
          </tr>
        </table><!--End cost table-->
      </div>
    </div>
  <?= $form->renderHiddenFields(); ?>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function()
  {
    'use strict';

    var $container = $('#shopping_cart_collectible_<?= $shopping_cart_collectible->getCollectibleId(); ?>');

    $container.find('.collectible-country').on('change', function()
    {
      var $this = $(this);

      $container.showLoading();

      // execute the JSON request only if a valid value is selected
      $this.val() && $.getJSON(
          '<?= url_for('ajax_shopping', array('section'=>'ShoppingCartCollectible', 'page' => 'UpdateCountry')) ?>',
          {
            collectible_id:  $this.data('collectible-id'),
            country_iso3166: $this.val()
          },
          function (data)
          {
            $container
                .load(
                '<?= url_for('@ajax_shopping?section=component&page=shoppingCartCollectible&collectible_id='.
                  $shopping_cart_collectible->getCollectibleId())?>',
                function()
                {
                  $container.hideLoading();
//                  $container.find('tr.rainbow-dash')
//                      .animate( { backgroundColor: "#ffffcc" }, 1)
//                      .animate( { backgroundColor: "#f3f1f1" }, 1500);
                }
            );
          }
      ); // getJSON()
    }); // on country change

    $container.find('.collectible-state').on('change', function()
    {
      var $this = $(this);

      $container.showLoading();

      // execute the JSON request only if a valid value is selected
      $this.val() && $.getJSON(
          '<?= url_for('ajax_shopping', array('section'=>'ShoppingCartCollectible', 'page' => 'UpdateState')) ?>',
          {
            collectible_id:  $this.data('collectible-id'),
            state: $this.val()
          },
          function (data)
          {
            $container
                .load(
                '<?= url_for('@ajax_shopping?section=component&page=shoppingCartCollectible&collectible_id=' .
                  $shopping_cart_collectible->getCollectibleId())?>',
                function()
                {
                  $container.hideLoading();
//                  $container.find('tr.rainbow-dash')
//                      .animate( { backgroundColor: "#ffffcc" }, 1)
//                      .animate( { backgroundColor: "#f3f1f1" }, 1500);
                }
            );
          }
      ); // getJSON()
    }); // on country change

    $container.find('.apply-promo').on('click', function()
    {
      $('.seller-promo .red', $container).html('');
      var $this = $(this);
      var val = $this.closest('div').find('input[name="checkout[promotion_code]"]').val();

      $container.showLoading();

      // execute the JSON request only if a valid value is selected
      $.getJSON(
          '<?= url_for('ajax_shopping', array('section'=>'ShoppingCartCollectible', 'page' => 'UpdatePromoCode')) ?>',
          {
            collectible_id:  $this.data('collectible-id'),
            code: val
          },
          function (data)
          {
            if (data.error)
            {
              $('.seller-promo .red', $container).html(data.error);
              $container.hideLoading();
            }
            else
            {
              $container
                  .load(
                  '<?= url_for('@ajax_shopping?section=component&page=shoppingCartCollectible&collectible_id=' .
                    $shopping_cart_collectible->getCollectibleId())?>',
                  function()
                  {
                    $container.hideLoading();
                  }
              );
            }
          }
      ); // getJSON()
    }); // on country change
  });
</script>
