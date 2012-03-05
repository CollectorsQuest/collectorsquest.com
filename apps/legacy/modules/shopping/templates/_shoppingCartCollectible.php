<?php
/**
 * @var $shopping_cart_collectible ShoppingCartCollectible
 * @var $form ShoppingCartCollectibleCheckoutForm
 */
?>

<div class="item_wrapper item-container-shadow">
  <div class="item_container cf">
    <div class="cf" style="border-bottom: 1px solid #DEDDDA; margin-bottom: 10px;">
      <div class="span-10"><!--Title-->
        <p class="title" style="margin: 0 0 0.5em 0">Seller <?= link_to_collector($shopping_cart_collectible->getCollector(), 'text'); ?></p>
      </div><!--End Title-->
      <div class="span-6 last"><!--Remove from cart-->
        <?= link_to('&nbsp;', '@shopping_cart_remove?id='. $shopping_cart_collectible->getCollectibleForSaleId(), array('class' => 'remove-from-cart')); ?>
      </div>
    </div>

    <div class="cf"><!--Item-->
      <div class="span-11">
        <div class="cf">
          <div class="span-2 append-10l"><!--Image-->
            <?= image_tag_collectible($shopping_cart_collectible->getCollectible(), '75x75'); ?>
          </div>
          <div class="span-7 last"><!--Info-->
            <p class="item-title-sc">
              <?= $shopping_cart_collectible->getName(); ?>
            </p>
            <p class="label-condition">
              <?= __('%condition% condition', array('%condition%' => $shopping_cart_collectible->getCondition())); ?>
            </p>
          </div><!--End Info-->
        </div>
        <div class="cf"><!--Note on the order-->
          <textarea id="note" class="simple-textarea" name="note" placeholder="If you have any special requests, please specify them here..."></textarea>
        </div>
      </div>
      <div class="span-5 last"><!--Prices-->
        <table><!--Start cost table-->
          <tr>
            <td>Price:</td>
            <td><?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?></td>
          </tr>
          <tr>
            <td>Shipping:</td>
            <td>Free</td>
          </tr>
          <tr class="rainbow-dash">
            <td><strong>Total cost:</strong></td>
            <td><strong><?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?></strong></td>
          </tr>
          <tr>
            <td colspan="2">
              Pay with: <img src="/images/legacy/payment/mini-logo-paypal.png" alt="" class="image-taxt-align" />
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <form action="<?= url_for('@shopping_cart_checkout'); ?>" method="post">
                <?= $form->renderHiddenFields(); ?>
                <button type="submit" class="newbutton red bolder-text" style="padding-top: 5px;">CHECKOUT</button>
              </form>
            </td>
          </tr>
        </table><!--End cost table-->
      </div>
    </div><!--End Item-->
  </div>
</div>
