
<?php if (!$pager->haveToPaginate() && ($suggestion = $pager->getDidYouMean($sf_params->get('q')))): ?>
<p class="alert alert-info">
  Did you mean: <strong><i><?= link_to($suggestion, '@search?q='. $suggestion); ?></i></strong>
</p>
<?php endif; ?>

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
<h1 class="Chivo webfont">Search results <small><?= $title; ?></small></h1>

<?php
  include_partial(
    'search/collectibles_for_sale',
    array('collectibles_for_sale' => $collectibles_for_sale)
  );
?>

<div class="row" style="margin-left: -10px;">
  <div id="search-results" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $object)
    {
      switch (strtolower(get_class($object)))
      {
        case 'wppost':
          echo '<div class="span8 brick fixed-height">';
          include_partial(
            '_blog/wp_post_'. $display .'_view',
            array(
              'wp_post' => $object, 'i' => $i,
              'title' => link_to_blog_post($object, 'text', array('truncate' => 80)),
              'excerpt' => $pager->getExcerpt($i))
          );
          echo '</div>';
          break;
        case 'collectible':
          echo '<div class="span4 brick fixed-height">';
          include_partial(
            'collection/collectible_'. $display .'_view',
            array('collectible' => $object, 'i' => $i, 'lazy_image' => 'all' != $sf_params->get('show'))
          );
          echo '</div>';
          break;
        case 'collection':
        case 'collectorcollection':
          echo '<div class="span4 brick fixed-height">';
          include_partial(
            'collection/collection_stack_'. $display .'_view',
            array('collection' => $object, 'excerpt' => $pager->getExcerpt($i), 'i' => $i)
          );
          echo '</div>';
          break;
        case 'collector':
          echo '<div class="span8 brick fixed-height">';
          include_partial(
            'collector/collector_'. $display .'_view_span8',
            array('collector' => $object, 'excerpt' => $pager->getExcerpt($i), 'i' => $i)
          );
          echo '</div>';
          break;
      }
    }
  ?>
  </div>
</div>

<div class="row-fluid text-center">
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
    window.cq.settings = $.extend(true, {}, window.cq.settings, {
      masonry: {
        add_infinite_scroll: <?= ($sf_params->get('show') == 'all') ? 'true' : 'false' ?>,
        loading_image: '<?= image_path('frontend/progress.gif'); ?>',
        loading_text: 'Loading more results...'
      }
    })
  });
</script>
