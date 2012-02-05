<?php include_partial('emails/header'); ?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?= sprintf(__('Dear %s', array(), 'emails'), $collector->getDisplayName()); ?>,
</p>
<p>
  Thank you for becoming a seller on Collectors' Quest. Below is your selected package information.<br/><br/>
  Package name: <strong><?= $package_name; ?></strong><br/>
  Allowed items for sale: <strong><?= $package_items; ?></strong><br/>
  Expiry date: <strong><?= date('m/d/Y', strtotime('+1 year')); ?></strong>
</p>

<?php include_partial('emails/footer'); ?>
