<?php
  /* @var $form_shipping_rates ShippingRatesCollectionForm */
?>

<?php include_partial('collector_addresses', array('collector_addresses' => $collector_addresses)); ?>

<?php include_partial('seller_shipping_rates', array('form' => $form_shipping_rates)); ?>
