<?php
/**
 * @var $collectibles_for_sale CollectibleForSale[]
 */
?>

<?php
  cq_sidebar_title(
    'Collectibles for Sale',
    link_to('See all &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'))
  );
?>

<div id="items-for-sale-sidebar">
<?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
  <div class="row-fluid">
    <div class="inner-border">
        <div class="span3">
          <?= ice_image_tag_flickholdr('60x60', array('i' => $collectible_for_sale->getCollectibleId())); ?>
          <?php link_to_collectible($collectible_for_sale->getCollectible(), 'image', array('width' => 60, 'height' => 60)); ?>
        </div>
        <div class="span9 fix-height-text-block">
          <div class="content-container">
          <?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target')); ?>
          <p>
            <?= cqStatic::truncateText($collectible_for_sale->getCollectible()->getDescription('stripped'), 35); ?>
          </p>
          <span class="price">$45.56</span>
          </div>
         </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
