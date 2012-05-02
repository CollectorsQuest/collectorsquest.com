<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<div class="span3 thumbnail link">
  <?php
    echo link_to_collectible(
      $collectible_for_sale->getCollectible(), 'image',
      array('width' => 150, 'height' => 150, 'max_width' => 132, 'max_height' => 132)
    );
  ?>
  <p>
    <?php
      echo link_to_collectible(
        $collectible_for_sale->getCollectible(), 'text',
        array('class' => 'target', 'truncate' => 20)
      );
    ?>
  </p>
  <span>
    <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
  </span>
</div>
