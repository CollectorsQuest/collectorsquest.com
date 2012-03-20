<?php
  $title = sprintf(
    'for <strong>%s</strong> (%s)',
    $sf_params->get('q'),
    format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults())
  );
  echo cq_page_title('Search results', $title);
?>

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
          echo '<div class="span8 brick" style="height: 165px; float: left;">';
          include_partial(
            'news/wp_post_grid_view',
            array('wp_post' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collectible':
          echo '<div class="span4 brick" style="height: 165px; float: left;">';
          include_partial(
            'collection/collectible_grid_view',
            array('collectible' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collection':
          echo '<div class="span4 brick" style="height: 165px; float: left;">';
          include_partial(
            'collection/collection_grid_view',
            array('collection' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collector':
          echo '<div class="span4 brick">';
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

<script>
  $(document).ready(function()
  {
    $('#main row').masonry({
      // options
      itemSelector : '.brick',
      columnWidth : 41.5
    });
  });
</script>
