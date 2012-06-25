<?php
/**
 * @var $shopping_cart_collectible ShoppingCartCollectible
 * @var $form ShoppingCartCollectibleCheckoutForm
 */
?>

<!-- <div class="shopping-cart-shadow"> -->
  <div class="shopping-cart-container-item">
    <div class="row-fluid">
      <div class="row-fluid line-under-title">
        <div class="span11"><!-- Title-->
          <p class="seller-link">Purchase from <?= link_to_collector($shopping_cart_collectible->getCollector(), 'text'); ?></p>
        </div><!-- /Title-->
        <div class="span1"><!-- Remove from cart-->
          <div class="pull-right">
            <?= link_to('&nbsp;', '@shopping_cart_remove?id='. $shopping_cart_collectible->getCollectibleId(), array('class' => 'remove-item')); ?>
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
            <table style="margin-bottom: 0;"><!--Start cost table-->
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
                <td style="text-align: right;">
                  <?= money_format('%.2n', (float) $shopping_cart_collectible->getPriceAmount()); ?>
                  <small style="font-size: 80%;"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
                </td>
              </tr>
              <tr>
                <td>Shipping:</td>
                <td style="text-align: right;">
                  <?php if ($shopping_cart_collectible->getShippingFeeAmount() > 0): ?>
                    <?= money_format('%.2n', (float) $shopping_cart_collectible->getShippingFeeAmount()); ?>
                    <small style="font-size: 80%;"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
                  <?php elseif (ShoppingCartCollectiblePeer::SHIPPING_TYPE_NO_SHIPPING == $shopping_cart_collectible->getShippingType()): ?>
                    <span class="red">Cannot be shipped to this country!</span>
                  <?php else: ?>
                    Free
                  <?php endif; ?>
                </td>
              </tr>
              <tr class="rainbow-dash">
                <td><strong>Total:</strong></td>
                <td style="text-align: right;">
                  <strong><?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?></strong>
                  <small style="font-size: 80%; font-weight: bold;"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
                </td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center;">
                  <button type="submit" name="Checkout" class="btn btn-primary" value="Checkout" style="width: 100%;">
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
  </div><!-- /shopping-cart-container-item -->
<!-- </div> /shadow-->
