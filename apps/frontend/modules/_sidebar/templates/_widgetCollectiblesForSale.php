<?php
/**
 * @var  $title  string
 * @var  $collectibles_for_sale  CollectibleForSale[]
 */
?>

<div id="collectibles-for-sale">
<?php
  cq_sidebar_title(
    $title, cq_link_to(
      'See all &raquo;',
      '@marketplace', array('class' => 'text-v-middle link-align')
    )
  );
?>

<div id="items-for-sale-sidebar">
<?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
  <div class="row-fluid">
    <div class="inner-border">
      <div class="span3">
        <?php
          echo link_to_collectible(
            $collectible_for_sale->getCollectible(), 'image',
            array('width' => 75, 'height' => 75, 'max_width' => 60)
          );
        ?>
      </div>
      <div class="span9 fix-height-text-block">
        <div class="content-container">
          <?php
            echo link_to_collectible(
              $collectible_for_sale->getCollectible(), 'text', array('class' => 'target')
            );
          ?>
          <p>
            <?= $collectible_for_sale->getCollectible()->getDescription(); ?>
          </p>
          <span class="price">
            <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
          </span>
        </div>
       </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

</div>
