<?php
/* @var $pager    cqSphinxPager */
/* @var $url      string */
/* @var $display  string */
/* @var $collectibles_for_sale  CollectibleForSale[] */
?>


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

<div class="row" style="margin-left: -15px;">
  <div id="search-results" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $object)
    {
      switch (strtolower(get_class($object)))
      {
        case 'wppost':
          if ($object->getPostType() == 'featured_items')
          {
            include_partial(
              'search/wp_featured_item',
              array(
                'blog_post' => $object,
                'url' => array('sf_route' => 'wordpress_featured_items', 'sf_subject' => $object)
              )
            );
          }
          elseif ($object->getPostType() == 'search_result')
          {
            $values = $object->getPostMetaValue('_search_result');
            $routing = $values['cq_route'];
            if ($routing)
            {
              include_partial(
                'search/wp_featured_item',
                array(
                  'blog_post' => $object,
                  'url' => url_for($routing)
                )
              );
            }
          }
          else
          {
            include_partial(
              'general/homepage_blogpost',
              array(
                'blog_post' => $object, 'i' => $i,
                'excerpt' => $pager->getExcerpt($i),
                'image' => false
              )
            );
          }
          break;
        case 'collectible':
          include_partial(
            'collection/collectible_grid_view_square_small',
            array('collectible' => $object, 'i' => $i)
          );
          break;
        case 'collection':
        case 'collectorcollection':
          include_partial(
            'collection/collection_grid_view_square_small',
            array('collection' => $object, 'excerpt' => $pager->getExcerpt($i), 'i' => $i)
          );
          break;
        case 'collector':
          include_partial(
            'collector/collector_grid_view_span6',
            array('collector' => $object, 'excerpt' => $pager->getExcerpt($i), 'i' => $i)
          );
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
