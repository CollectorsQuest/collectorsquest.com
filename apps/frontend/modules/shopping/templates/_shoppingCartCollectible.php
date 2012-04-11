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
        <?= link_to('&nbsp;', '@shopping_cart_remove?id='. $shopping_cart_collectible->getCollectibleId(), array('class' => 'remove-from-cart')); ?>
      </div>
    </div>

    <form action="<?= url_for('@shopping_cart_checkout'); ?>" method="post">
    <div class="cf"><!--Item-->
      <div class="span-11">
        <div class="cf" style="min-height: 120px;">
          <div class="span-2 append-10l"><!--Image-->
            <?= image_tag_collectible($shopping_cart_collectible->getCollectible(), '75x75'); ?>
          </div>
          <div class="span-8 last"><!--Info-->
            <p class="item-title-sc">
              <?= link_to_collectible($shopping_cart_collectible->getCollectible(), 'text'); ?>
              -
              <span class="label-condition" style="font-weight: normal; font-size: 80%;">
                <?= __('%condition% condition', array('%condition%' => $shopping_cart_collectible->getCondition())); ?>
              </span>
            </p>
            <p><?= $shopping_cart_collectible->getDescription(); ?></p>
          </div><!--End Info-->
        </div>
        <div class="cf"><!--Note on the order-->
          <?php
            echo $form['note_to_seller']->render(array(
              'class' => 'simple-textarea',
              'placeholder' => 'If you have any special requests, please specify them here...'
            ));
          ?>
        </div>
      </div>
      <div class="span-5 last"><!--Prices-->
        <table style="margin-bottom: 0;"><!--Start cost table-->
          <tr>
            <td colspan="2">
              Ship to:<br/>
              <?= $form['country_iso3166']->render(array('style' => 'width: 100%;')); ?>
            </td>
          </tr>
          <tr>
          <tr>
            <td>Price:</td>
            <td>
              <?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?>
              <small style="font-size: 80%;"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <tr>
            <td>Shipping:</td>
            <td>Free</td>
          </tr>
          <tr class="rainbow-dash">
            <td><strong>Total:</strong></td>
            <td>
              <strong><?= money_format('%.2n', (float) $shopping_cart_collectible->getTotalPrice()); ?></strong>
              <small style="font-size: 80%;"><?= $shopping_cart_collectible->getPriceCurrency(); ?></small>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">
              <button type="submit" name="Checkout" class="btn btn-large btn-checkout" value="Checkout" data-loading-text="loading...">
                Checkout
              </button>
            </td>
          </tr>
        </table><!--End cost table-->
      </div>
    </div><!--End Item-->

    <?= $form->renderHiddenFields(); ?>
    </form>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('.btn-checkout').button();
    $('.btn-checkout').click(function()
    {
      $(this).button('loading');
    });
  });
</script>
