<?php
  /* @var $collector_addresses CollectorAddress[] */
  /* @var $address CollectorAddress */
?>

<div class="collector-addresses-holder">
  <h2>Your addresses</h2>

  <?php if (count($collector_addresses)): ?>
    <p>Your addresses:</p>
    <div class="collector-addresses">

    <?php foreach ($collector_addresses as $key => $address): ?>
      <div class="address-row">
        <span><?= $key+1 ?>.</span>
        <div class="address-data">
          <?php include_partial('collector_address',
             array('collector_address' => $address)); ?>
          <div class="actions">
            <a href="<?= url_for(array('sf_route' => 'manage_collector_address_edit', 'sf_subject' => $address)) ?>">Edit</a>
            <a href="<?= url_for(array('sf_route' => 'manage_collector_address_delete', 'sf_subject' => $address)) ?>">Delete</a>
          </div>
        </div> <!-- .address-data -->
      </div> <!-- .address-row -->
    <?php endforeach; ?>
    </div> <!-- .collector-addresses -->

  <?php else: ?>
    <p>
      You have no addresses currently entered. <br/>
      Click here to add a new address:
      <a href="<?= url_for('@manage_collector_address_new') ?>">Enter a new address</a>
    </p>
  <?php endif; ?>
</div> <!-- .collector-address-holder -->