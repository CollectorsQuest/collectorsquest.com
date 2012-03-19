<?= cq_page_title('Search results', 'for '. $sf_params->get('q')); ?>

<div class="row">
  <?php
    foreach ($pager->getResults() as $i => $object)
    {
      /**
       * @see: http://mulu.me/
       */
      switch (strtolower(get_class($object)))
      {
        case 'wppost':
          echo '<div class="span4">';
          include_partial(
            'news/wp_post_grid_view',
            array('wp_post' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collectible':
          echo '<div class="span4">';
          include_partial(
            'collection/collectible_grid_view',
            array('collectible' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collection':
          echo '<div class="span4">';
          include_partial(
            'collection/collection_grid_view',
            array('collection' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collector':
          echo '<div class="span4">';
          include_partial(
            'collector/collector_grid_view',
            array('collector' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
      }

    }
  ?>
</div>
