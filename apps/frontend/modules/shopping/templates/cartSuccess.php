<?php
/**
 * @var $shopping_cart ShoppingCart
 * @var $shopping_cart_collectibles ShoppingCartCollectible[]
 */
?>

<?php
  $title = format_number_choice(
    '[0] your shopping cart is empty!|[1] you have 1 item in your shopping cart|(1,+Inf] you have %1% items in your shopping cart',
    array('%1%' => count($shopping_cart_collectibles)), count($shopping_cart_collectibles)
  )
?>
<h1 class="Chivo webfont" style="font-size: 24px; font-weight: normal;">Shopping Cart <small> - <?= $title; ?></small></h1>
<br/>

<div id="shopping_cart_collectibles">
<?php
  foreach ($shopping_cart_collectibles as $shopping_cart_collectible)
  {
    echo sprintf(
      '<div class="shopping-cart-container-item" id="shopping_cart_collectible_%d">',
      $shopping_cart_collectible->getCollectibleId()
    );
    include_component(
      'shopping', 'shoppingCartCollectible',
      array('shopping_cart_collectible' => $shopping_cart_collectible)
    );
    echo '</div>';
  }
?>
</div>
