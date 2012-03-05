<?php
/**
 * @var $shopping_cart ShoppingCart
 * @var $shopping_cart_collectibles ShoppingCartCollectible[]
 */
  use_stylesheet('legacy/shopping-cart.css');
?>

<br/>
<?php
  foreach ($shopping_cart_collectibles as $shopping_cart_collectible)
  {
    include_component(
      'shopping', 'shoppingCartCollectible',
      array('shopping_cart_collectible' => $shopping_cart_collectible)
    );
  }
?>
