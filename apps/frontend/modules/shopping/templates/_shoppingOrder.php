<?php
/**
 * @var $shopping_cart_collectible ShoppingCartCollectible
 * @var $form ShoppingCartCollectibleCheckoutForm
 */
/** @var $form ShoppingOrderCheckoutForm*/
/** @var $shopping_order ShoppingOrder */
?>

<div class="row-fluid">
  <div class="row-fluid line-under-title">
    <div class="span11"><!-- Title-->
      <p class="seller-link">Purchase from <?= link_to_collector($shopping_order->getSeller(), 'text'); ?></p>
    </div><!-- /Title-->
    <div class="span1"><!-- Remove from cart-->
      <div class="pull-right">
      </div>
    </div><!-- /Remove from cart-->
  </div>

  <form action="<?= url_for('@shopping_cart_checkout'); ?>" method="post">
    <?php foreach ($shopping_order->getShoppingOrderCollectibles() as $shopping_order_collectible): ?>
    <div class="row-fluid shopping-cart-item">
      <div class="span2 sc-thumb"><!--Image-->
        <?= image_tag_collectible($shopping_order_collectible->getCollectible(), '75x75'); ?>
      </div>
      <div class="span6"><!--Info-->
        <?= link_to_collectible($shopping_order_collectible->getCollectible(),
          'text', array('class' => 'title-item')); ?>
        <span class="label-condition">
          <?= __('%condition% condition',
          array('%condition%' => $shopping_order_collectible->getCondition())); ?>
        </span>
        <p><?= $shopping_order_collectible->getCollectible()->getDescription('stripped', 100); ?></p>
        <input type="hidden" name="<?= $form['collectibles']->renderName() ?>[]"
               value="<?= $shopping_order_collectible->getCollectibleId() ?>"/>
      </div>
      <div class="span4">
        <div class="span12"><!-- Remove from cart-->
          <div class="pull-right">
            <?= link_to('<i class="icon-remove"></i>',
            '@shopping_cart_remove?id='. $shopping_order_collectible->getCollectibleId(),
            array('class' => 'close-button', 'rel' => 'tooltip', 'title' => 'Remove Item')); ?>
          </div>
        </div><!-- /Remove from cart-->

        <table class="table-shopping-cart"><!--Start cost table-->
            <tr>
                <td>Price:</td>
                <td class="text-right">
                  <?= money_format('%.2n', (float) $shopping_order_collectible->getPriceAmount()); ?>
                    <small><?= $shopping_order_collectible->getPriceCurrency(); ?></small>
                </td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td class="text-right">
                  <?php if ($shopping_order_collectible->isCannotShip()): ?>
                    <span class="red">
                      Cannot be shipped to <?= $shopping_order_collectible->getShippingCountryName(); ?>!
                    </span>
                  <?php elseif ($shopping_order_collectible->getShippingFeeAmount() > 0): ?>
                    <?= money_format('%.2n', (float) $shopping_order_collectible->getShippingFeeAmount()); ?>
                    <small><?= $shopping_order_collectible->getPriceCurrency(); ?></small>
                  <?php else: ?>
                    Free
                  <?php endif; ?>
                </td>
            </tr>
        </table><!--End cost table-->

      </div>
    </div>

    <?php endforeach; ?>
    <div class="row-fluid">
      <div class="span8">

      </div>
      <div class="span4">
          Ship to:<br/>
        <?= $form['shipping_country_iso3166']->render(array(
        'style' => 'width: 100%;',
        'class' => 'collectible-country',
        'data-group-key' => $shopping_order->getGroupKey(),
      )); ?>
      </div>
    </div>

    <div class="row-fluid">

      <div class="span8"><!--Note on the order-->
        <?php
        echo $form['note_to_seller']->render(array(
          'class' => 'simple-textarea',
          'placeholder' => 'If you have any special requests, please specify them here...'
        ));
        ?>
        </div>
      <div class="span4">
        <table class="table-shopping-cart"><!--Start cost table-->
          <tr class="rainbow-dash">
            <td>Shipping:</td>
            <td class="text-right">
              <?php  if ($shopping_order->isCannotShip()): ?>
          <span class="red">Cannot be shipped!</span>
          <?php elseif ($shopping_order->getShippingFeeTotalAmount() > 0): ?>
          <?= money_format('%.2n', (float) $shopping_order->getShippingFeeTotalAmount()); ?>
          <small><?= $shopping_order->getPriceCurrency(); ?></small>
          <?php else: ?>
          Free
          <?php endif;  ?>
            </td>
          </tr>
          <tr>
            <td><strong>Total:</strong></td>
            <td class="text-right">
              <strong><?= money_format('%.2n', (float) $shopping_order->getTotalPrice()); ?></strong>
              <small class="text-bold"><?= $shopping_order->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="text-center">
              <button type="submit" name="Checkout"
                <?= (!$shopping_order->isCannotShip() ?: 'disabled="disabled"'); ?>
                      class="btn btn-primary" value="Checkout" style="width: 100%;">
                Proceed to Checkout
              </button>
              <div class="text-left spacer-top">
                  or you can send a message to
                  <?php
                    $seller = $shopping_order->getSeller();

                    echo link_to($seller->getDisplayName(), 'ajax_messages',
                      array(
                        'section' => 'message',
                        'page' => 'send',
                        'to' => (string) $seller->getUsername(),
                        'item' => $shopping_order_collectible->getCollectible()->getName()
                      ),
                      array('class' => 'open-dialog', 'onclick' => 'return false;')
                    );
                  ?>
              </div>
            </td>
          </tr>
        </table><!--End cost table-->
      </div>
    </div>
    <?= $form->renderHiddenFields(); ?>
  </form>
</div>



<?php /*


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
          <tr>
          <tr>
            <td>Price:</td>
            <td class="text-right">
              <?= money_format('%.2n', (float) $shopping_cart_collectible->getPriceAmount()); ?>
              <small><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
            </td>
          </tr>
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
*/ ?>
<script type="text/javascript">
$(document).ready(function()
{
  'use strict';

  var $container = $('#shopping_order_<?= $shopping_order->getGroupKey(); ?>');

  $container.find('.collectible-country').on('change', function()
  {
    var $this = $(this);
    $container.showLoading();

    // execute the JSON request only if a valid value is selected
    $this.val() && $.getJSON(
      '<?= url_for('ajax_shopping', array('section'=>'ShoppingCartCollectible', 'page' => 'UpdateCountry')) ?>',
      {
        group_key:  $this.data('group-key'),
        country_iso3166: $this.val()
      },
      function (data)
      {
        $container
          .load(
            '<?= url_for('@ajax_shopping?section=component&page=shoppingOrder&group_key='. $shopping_order->getGroupKey())?>',
            function()
            {
              $container.hideLoading();
//              $container.find('tr.rainbow-dash')
//                .animate( { backgroundColor: "#ffffcc" }, 1)
//                .animate( { backgroundColor: "#f3f1f1" }, 1500);
            }
          );
      }
    ); // getJSON()
  }); // on country change

});
</script>
