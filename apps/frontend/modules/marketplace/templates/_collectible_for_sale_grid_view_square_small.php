<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<div class="span3 thumbnail link">
  <?php
    link_to_collectible(
      $collectible_for_sale->getCollectible(), 'image',
      array('width' => 131, 'height' => 131)
    );
  ?>
  <?= ice_image_tag_placeholder('131x131'); ?>
  <p><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target', 'truncate' => 20)); ?></p>
  <span><?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?></span>
</div>
