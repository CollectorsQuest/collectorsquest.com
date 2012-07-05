<div class="row thumbnails">
  <?php
    foreach ($pager->getResults() as $i => $collectible)
    {
      if ($collectible->isForSale())
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square_small',
          array(
            'collectible_for_sale' => $collectible->getCollectibleForSale(),
            'i' => (integer) $i
          )
        );
      }
      else
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'collection/collectible_grid_view_square_small_bordered',
          array(
            'collectible' => $collectible,
            'i' => (integer) $i
          )
        );
      }


      // if we have outputed 4 results (one row) and there are more rows to come
      if (($i+1)%4 == 0 && ($i+1) < $pager->getMaxPerPage() && ($i+1) != $pager->count())
      {
        // close this row and open the next
        echo '</div><div class="row thumbnails">';
      }
    }
  ?>
</div>

<div class="row-fluid" style="text-align: center;">
<?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
?>
</div>
