
<div class="shopping-cart-container-items">
  <div class="row-fluid">
    <div class="row-fluid">
      <div class="span8">
        <div class="row-fluid">
          <div class="span3 sc-thumb"><!--Image-->
            <?= image_tag_collectible($shopping_order->getCollectible(), '75x75'); ?>
          </div>
          <div class="span9"><!--Info-->
            <?= link_to_collectible($shopping_order->getCollectible(), 'text', array('class' => 'title-item')); ?>
            <span class="label-condition">
                <?= __('%condition% condition', array('%condition%' => $shopping_order->getCollectibleForSale()->getCondition())); ?>
              </span>
            <p><?= $shopping_order->getCollectible()->getDescription(); ?></p>
          </div>
        </div>
        <div><!--Note on the order-->
          Note to seller
        </div>
      </div><!-- /span8 -->
      <div class="span4">
        <table class="spacer-bottom-reset"><!--Start cost table-->
          <tr class="rainbow-dash">
            <td>Price:</td>
            <td class="text-right">
              <?= money_format('%.2n', (float) $shopping_order->getTotalPrice()); ?>
              <small><?= $shopping_order->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <tr>
            <td>Shipping:</td>
            <td class="text-right">
              <?php if ($shopping_cart_collectible->getShippingFeeAmount() > 0): ?>
              <?= money_format('%.2n', (float) $shopping_cart_collectible->getShippingFeeAmount()); ?>
              <small><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
              <?php else: ?>
              Free
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td><strong>Total:</strong></td>
            <td class="text-right">
              <strong><?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?></strong>
              <small class="text-bold"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="text-center">
              <button type="submit" name="Checkout" class="btn btn-large btn-danger" value="Checkout" style="width: 100%;">
                Proceed to Checkout
              </button>
            </td>
          </tr>
        </table><!--End cost table-->
      </div>
    </div>

  </div>
</div>
