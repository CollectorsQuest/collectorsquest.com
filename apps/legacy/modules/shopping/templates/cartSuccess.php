<?php
  /**
   * @var $shopping_cart ShoppingCart
   * @var $collectibles_for_sale CollectibleForSale[]
   */

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
