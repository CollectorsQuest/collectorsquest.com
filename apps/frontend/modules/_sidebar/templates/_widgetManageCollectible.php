<?php
/**
 * @var $collectible Collectible|CollectionCollectible
 */
?>

<div class="well" style="padding: 8px 0;">
  <ul class="nav nav-list">
    <li class="nav-header">Owner Options:</li>
    <li>
      <a rel="nofollow" href="<?= url_for('mycq_collectible_by_slug', $collectible); ?>">
        <i class="icon-edit"></i>
        Edit Collectible
      </a>
    </li>
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
