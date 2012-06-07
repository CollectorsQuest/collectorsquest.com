<?php
  /* @var $collector_address CollectorAddress */
?>

<p>
  Click the Confirm button below to permanently remove this
  address from your address book.
</p>

<div class="address-data">
  <?php include_partial('collector_address',
     array('collector_address' => $collector_address)); ?>
  <div class="actions">
    <?php echo link_to('Delete', array(
        'sf_route' => 'manage_collector_address_delete',
        'sf_subject' => $collector_address,
      ), array(
        'method' => 'delete',
    )); ?>
  </div>
</div> <!-- .address-data -->