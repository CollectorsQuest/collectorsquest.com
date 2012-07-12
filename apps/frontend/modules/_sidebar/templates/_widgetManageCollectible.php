<?php
/**
 * @var $collectible Collectible|CollectionCollectible
 */
?>

<div class="well">
  <div class="row-fluid">
    <div class="span8">
      <ul class="nav nav-list spacer-inner-left-reset">
        <li class="nav-header">Owner Options:</li>
        <?php if ($collectible->isWasForSale() && $collectible->getCollectibleForSale()->getIsSold()): ?>
        <li>
          <a rel="nofollow" href="<?= url_for('mycq_collectible_by_slug', $collectible); ?>">
            <i class="icon-credit-card"></i>
            View transaction details
          </a>
        </li>
        <?php else: ?>
        <li>
          <a rel="nofollow" href="<?= url_for('mycq_collectible_by_slug', $collectible); ?>">
            <i class="icon-edit"></i>
            Edit Collectible
          </a>
        </li>
        <?php endif; ?>
        <?php /**
            <li>
            <a rel="nofollow" href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => 1)); ?>">
            <i class="icon-trash"></i>
            Delete Collectible
            </a>
            </li>
        */ ?>
      </ul>
    </div>
    <div class="span4">
      <i class="wrench-well-icon pull-right spacer-top"></i>
    </div>
  </div>

</div>
