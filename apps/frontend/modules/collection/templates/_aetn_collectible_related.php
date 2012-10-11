<?php cq_section_title($title ?: 'Showcase', null); ?>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    if (!empty($related_collectibles))
    {
      /** @var $related_collectibles Collectible[] */
      foreach ($related_collectibles as $i => $collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectible, 'i' => $i)
        );
      }
    }
    else if (!empty($related_collections))
    {
      foreach ($related_collections as $i => $collection)
      {
        include_partial(
          'collection/collection_grid_view_square_small',
          array('collection' => $collection, 'i' => $i)
        );
      }
    }
    ?>
  </div>
</div>
<?php $height->value += 379; ?>
