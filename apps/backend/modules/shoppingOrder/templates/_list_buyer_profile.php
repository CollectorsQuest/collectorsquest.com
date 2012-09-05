<?php
  /** @var $ShoppingOrder ShoppingOrder */
  $collector = $ShoppingOrder->getCollectorRelatedByCollectorId();
?>

<?php if ($collector): ?>
<a href="javascript:void(0)" rel="clickover" data-width="300" data-placement="top"
   data-original-title='<?= link_to($collector->getDisplayName(), 'collector_edit', $collector); ?>'
   data-content='<?php include_partial('popover_buyer_profile', array('collector' => $collector)); ?>'>
  <i class="icon-user"></i>
</a>
<?php else: ?>
<center>-</center>
<?php endif; ?>
