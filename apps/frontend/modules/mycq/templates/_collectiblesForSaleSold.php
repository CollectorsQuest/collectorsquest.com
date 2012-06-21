
<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>
  <div class="span3 collectible_sold_items_grid_view_square link">
    <?php
    echo image_tag_collectible(
      $collectible_for_sale->getCollectible(), '140x140',
      array('width' => 130, 'height' => 130)
    ); ?>
    <span class="sold">SOLD</span>
    <p>
      <?php
        echo cqStatic::truncateText(
          $collectible_for_sale->getCollectible()->getName(), 18, '...', true
        );
      ?>
      <br/>
      <strong class="pull-right">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </strong>
    </p>
  </div>
  <?php endforeach; ?>

<?php endif; ?>
