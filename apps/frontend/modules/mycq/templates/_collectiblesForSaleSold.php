<?php
/* @var $pager PropelModelPager */
/* @var $shopping_order ShoppingOrder */
?>

<?php foreach ($pager->getResults() as $i => $shopping_order): ?>
<div class="span3 collectible_sold_items_grid_view_square link">
  <?php
    echo link_to(image_tag_collectible(
      $shopping_order->getCollectible(), '140x140',
      array('width' => 130, 'height' => 130)
    ), '@mycq_transaction?uuid=' . $shopping_order->getUuid());
  ?>
  <span class="sold">SOLD</span>
  <p>
    <?php
      echo link_to(
        cqStatic::truncateText(
          $shopping_order->getCollectible()->getName(), 36, '...', true
        ),
        '@mycq_transaction?uuid=' . $shopping_order->getUuid(),
        array('class' => 'target')
      );
    ?>
    <strong class="pull-right">
      <?= money_format('%.2n', (float) $shopping_order->getCollectibleForSale()->getPrice()); ?>
    </strong>
  </p>
</div>
<?php endforeach; ?>
