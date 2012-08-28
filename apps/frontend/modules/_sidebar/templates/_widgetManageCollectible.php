<?php
/**
 * @var $collectible Collectible|CollectionCollectible
 * @var $height stdClass
 */

$_height = 0;
?>

<div class="well">
  <div class="row-fluid">
    <div class="span8">
      <ul class="nav nav-list spacer-inner-left-reset">
        <li class="nav-header">Owner Options:</li>
        <?php if ($collectible->isWasForSale() && $collectible->getCollectibleForSale()->getIsSold()): ?>
        <li>
          <a rel="nofollow" href="<?= url_for('mycq_collectible_by_slug', $collectible); ?>">
            <i class="icon-credit-card"></i> View transaction details
          </a>
        </li>
        <?php else: ?>
        <li>
          <a rel="nofollow" href="<?= url_for('mycq_collectible_by_slug', $collectible); ?>">
            <i class="icon-edit"></i> Edit Item
          </a>
        </li>
        <li>
          <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => 1)); ?>"
             rel="nofollow" onclick="return confirm('Are you sure you want to delete this item from all collections?');">
            <i class="icon-trash"></i> Delete Item
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="span4">
      <i class="wrench-well-icon pull-right spacer-top"></i>
    </div>
  </div>

</div>
<?php $_height -= 87; ?>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
