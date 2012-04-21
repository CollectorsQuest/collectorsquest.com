<?php
  include_partial(
    'search/display_toggle',
    array('url' => $url, 'display' => $display)
  );
?>

<?php
  $title = sprintf(
    'for <strong>%s</strong> (%s)',
    $sf_params->get('q'),
    format_number_choice(
      '[0] no results|[1] 1 result|(1,+Inf] %1% results',
      array('%1%' => $pager->getNbResults()), $pager->getNbResults()
    )
  );
?>
<h1>Search results <small><?= $title; ?></small></h1>

<div class="row">
  <div id="search-results" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $object)
    {
      switch (strtolower(get_class($object)))
      {
        case 'wppost':
          echo '<div class="span8 brick" style="height: 165px; float: left;">';
          include_partial(
            'news/wp_post_'. $display .'_view',
            array('wp_post' => $object, 'excerpt' => $pager->getExcerpt($i), 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collectible':
          echo '<div class="span4 brick" style="height: 165px; float: left;">';
          include_partial(
            'collection/collectible_'. $display .'_view',
            array('collectible' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collection':
        case 'collectorcollection':
          echo '<div class="span4 brick" style="height: 165px; float: left;">';
          include_partial(
            'collection/collection_stack_'. $display .'_view',
            array('collection' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collector':
          echo '<div class="span4 brick" style="height: 165px; float: left;">';
          include_partial(
            'collector/collector_'. $display .'_view',
            array('collector' => $object, 'i' => $i)
          );
          echo '</div>';
          break;
      }
    }
  ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
  <?php
    include_component(
      'global', 'pagination',
      array('pager' => $pager, 'options' => array('id' => 'search-pagination', 'show_all' => true))
    );
  ?>
</div>

<script>
  $(document).ready(function()
  {
    $.extend(cq.settings, {
      masonry: {
        add_infinite_scroll: <?= ($sf_params->get('show') == 'all') ? 'true' : 'false' ?>,
        loading_image: '<?= image_path('frontend/progress.gif'); ?>'
      }
    })
  });
</script>
