
<?php if ($pager->getNbResults() > 0): ?>

    <?php foreach ($pager->getResults() as $i => $shopping_order): ?>
    <div class="span3 collectible_sold_items_grid_view_square link">
      <?php
        echo image_tag_collectible(
          $shopping_order->getCollectible(), '140x140',
          array('width' => 130, 'height' => 130)
        ); ?>
      <span class="sold">BOUGHT</span>
      <p>
        <?php
          echo cqStatic::truncateText(
            $shopping_order->getCollectible()->getName(), 18, '...', true
          );
        ?>
        <br/>
        <strong class="pull-right">
          <?= money_format('%.2n', (float) $shopping_order->getCollectibleForSale()->getPrice()); ?>
        </strong>
      </p>
    </div>
    <?php endforeach; ?>

<?php endif; ?>
