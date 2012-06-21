<?php
/**
 * @var  $seller  Collector
 * @var  $shopping_order    ShoppingOrder
 * @var  $shopping_payment  ShoppingPayment
 */
?>

<script>
  if (window != top) {
    top.location.replace(document.location);
  }
</script>

<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping'), __('Payment'), __('Order Review')) , 'active' => 3)
  );
?>

<h2 class="spacer-top-35">
  <?= $shopping_order->getShippingFullName() ?>, thank you for your purchase!
</h2>

<p class="spacer-top-20">
  The seller, <?= link_to_collector($seller) ?>, has been notified and
  will be in contact with you soon to finalize any outstanding details if needed.
  You should receive a confirmation email when you item ships.
</p>

<?php if (!$sf_user->isAuthenticated()): ?>
  <p>
    To better track your order and to ease your future the shopping experiece,
    you can <strong>signup for an account</strong> using the form to the right.
  </p>
<?php endif; ?>

<br/>
<p>Here are your transaction details:</p>

[Item thumbnail, usernames, e-mail addresses, link to communicate on CQ, physical addresses, price, shipping, etc.]

<?php
//  include_partial(
//    'shopping/shoppingOrderReview',
//    array('shopping_order' => $shopping_order)
//  );
?>
