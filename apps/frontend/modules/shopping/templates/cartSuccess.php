<?php
/**
 * @var $shopping_orders ShoppingOrder[]
 * @var $shopping_cart_collectibles ShoppingCartCollectible[]
 */
?>

<?php
  $title = format_number_choice(
    '[0] your shopping cart is empty!|[1] you have 1 item in your shopping cart|(1,+Inf] you have %1% items in your shopping cart',
    array('%1%' => count($shopping_cart_collectibles)), count($shopping_cart_collectibles)
  )
?>
<h1 class="Chivo webfont" style="font-size: 24px; font-weight: normal;">
  Shopping Cart <small> - <?= $title; ?></small>
</h1>
<br/>

<div id="shopping_cart_collectibles">
<?php
foreach ($shopping_orders as $group_key => $shopping_order)
{
  echo sprintf(
      '<div class="shopping-cart-container-items" id="shopping_order_%s">',
      $group_key
    );
  include_component(
      'shopping', 'shoppingOrder',
      array('shopping_order' => $shopping_order)
    );
  echo '</div>';
}
?>
</div>
