
<?php if ($pager->getNbResults() > 0): ?>

    <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>
    <div class="span3 collectible_sold_items_grid_view_square link">
      <a href="#">
        <img alt="" src="http://placehold.it/130x130">
      </a>
      <span class="sold">SOLD</span>
      <p>
        <a href="#" class="target" title="Transformer - Perfect...">
          Transformer - Perfect...
        </a>
        <strong class="pull-right">$15.00</strong>
      </p>
    </div>
    <?php endforeach; ?>

<?php endif; ?>
