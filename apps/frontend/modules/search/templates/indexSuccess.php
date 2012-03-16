<?= cq_page_title('Search results', 'for '. $sf_params->get('q')); ?>

<div class="row">
  <?php
    foreach ($collectibles as $i => $collectible)
    {
      echo '<div class="span4">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_grid_view',
        array(
          'collectible' => $collectible,
          'culture' => $sf_user->getCulture(),
          'i' => $i
        )
      );
      echo '</div>';
    }
  ?>
</div>
