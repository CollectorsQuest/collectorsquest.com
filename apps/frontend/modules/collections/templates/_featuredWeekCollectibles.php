<div class="row">
  <?php
    /** @var $collectibles Collectible[] */
    foreach ($collectibles as $i => $collectible)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_grid_view_square_small',
        array(
          'collectible' => $collectible,
          'culture' => (string) $sf_user->getCulture(),
          'i' => (int) $i
        )
      );
    }
  ?>
</div>
