<?php
/**
 * @var $shopping_cart ShoppingCart
 * @var $collectibles_for_sale CollectibleForSale[]
 */
  use_stylesheet('legacy/shopping-cart.css');
?>
<p class="title-shopping-cart">SHOPPING CART</p>

<?php
  foreach ($collectibles_for_sale as $collectible_for_sale)
  {
    $collectible = $collectible_for_sale->getCollectible();
    $seller = $collectible_for_sale->getCollector();

    $checkout = new ShoppingCartCollectibleCheckoutForm(array(
      'shopping_cart_id' => $shopping_cart->getId(),
      'collectible_for_sale_id' => $collectible_for_sale->getId()
    ));

    echo implode('&nbsp|&nbsp', array(
      $seller->getDisplayName(),
      image_tag_collectible($collectible),
      $collectible->getName(),
      $collectible_for_sale->getPrice()
    ));

    echo $checkout->renderFormTag(url_for('@shopping_cart_checkout'), array('target' => '_blank'));
    echo $checkout->render();
    echo '<input type="submit" value="Checkout">';
    echo '</form>';

    echo '<br/><br/>';
  }
?>

<div class="item_wrapper item-container-shadow">
  <div class="item_container cf">
    <div class="cf">
      <div class="span-10"><!--Title-->
        <p class="title">
          From <a href="/" title="Bmwq4577" alt="Bmwq4577">Bmwq4577</a>
        </p>
      </div><!--End Title-->
      <div class="span-6 last"><!--Remove from cart-->
        <a href="" class="remove-from-cart" title="Remove from cart"></a>
      </div>
    </div>

    <div class="cf"><!--Item-->
      <div class="span-11">
        <div class="cf">
          <div class="span-2 append-10l"><!--Image-->
             <img class="thumbnail" src="/images/legacy/multimedia/Collectible/75x75.png" alt="" />
          </div>
          <div class="span-7 last"><!--Info-->
            <p class="item-title-sc">
              title
            </p>
            <p class="label-condition">
              Excellent condition
            </p>
          </div><!--End Info-->
        </div>
        <div class="cf"><!--Note on the order-->
          <textarea id="note" class="simple-textarea" name="note" placeholder="If you have any notes on the order, please type them here"></textarea>
        </div>
        </div>
      <div class="span-5 last"><!--Prices-->
        <table><!--Start cost table-->
          <tr>
            <td>
              Price:
            </td>
            <td>
              $555550.00
            </td>
          </tr>
          <tr>
            <td>
              Shipping:
            </td>
            <td>
              Free
            </td>
          </tr>
          <tr class="rainbow-dash">
            <td>
              <strong>Total cost:</strong>
            </td>
            <td>
              <strong>$50.00</strong>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              Payment method: <img src="/images/legacy/payment/mini-logo-paypal.png" alt="" class="image-taxt-align" />
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <a class="newbutton red bolder-text">CHECKOUT</a>
            </td>
          </tr>
        </table><!--End cost table-->
      </div>
    </div><!--End Item-->
  </div>
</div>
