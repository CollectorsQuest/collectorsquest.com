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

<?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
<div class="row-fluid link">
  <div style="border: 1px solid #dcd7d7; margin-top: 10px;">
    <div style="border: 1px solid #f2f1f1; padding: 2px;">
      <div class="span3">
        <?= ice_image_tag_flickholdr('60x60', array('i' => $i)); ?>
        <?php link_to_collectible($collectible_for_sale->getCollectible(), 'image', array('width' => 60, 'height' => 60)); ?>
      </div>
      <div class="span9" style="padding-top: 5px;">
        <?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target')); ?><br/>
        <?= cqStatic::truncateText($collectible_for_sale->getCollectible()->getDescription(), 35); ?><br/>
        <span style="float: right; font-weight: bold; margin-right: 5px;">$45.56</span>
      </div>
      <br clear="all"/>
    </div>
  </div>
</div>
<?php endforeach; ?>
