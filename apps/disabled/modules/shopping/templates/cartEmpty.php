<?php
  use_stylesheet('legacy/shopping-cart.css');
?>
<br/><br/>
<div class="empty_cart_message">
  <p>Your cart is empty.</p>
  <div class="button-center">
    <?= cq_button('See the Marketplace', '@marketplace'); ?>
  </div>
</div>
