<div id="search-display" class="btn-group"  data-toggle="buttons-radio" style="float: right; margin-top: 20px;">
  <button class="btn"><i class="icon-th"></i></button>
  <button class="btn"><i class="icon-th-list"></i></button>
</div>

<?php
  $title = sprintf(
    'for <strong>%s</strong> (%s)',
    $sf_params->get('q'),
    format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults())
  );
  cq_page_title('Search results', $title);
?>

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
      array('pager' => $pager, 'options' => array('id' => 'search-pagination'))
    );
  ?>
</div>

<script>
  $(document).ready(function()
  {
    var $container = $('#search-results');

    $container.imagesLoaded(function() {
      $container.masonry(
      {
        itemSelector : '.brick',
        columnWidth : 201, gutterWidth: 15,
        isAnimated: !Modernizr.csstransitions
      });
    });

    <?php if ($sf_params->get('show') == 'all'): ?>
      $container.infinitescroll(
      {
        navSelector: '#search-pagination',
        nextSelector: '#search-pagination li.next a',
        itemSelector: '.brick',
        loading:
        {
          finishedMsg: 'No more pages to load.',
          img: '<?= image_path('frontend/progress.gif'); ?>'
        },
        bufferPx: 150
      },
      // trigger Masonry as a callback
      function(selector)
      {
        // hide new bricks while they are loading
        var $bricks = $(selector).css({ opacity: 0 });

        // ensure that images load before adding to masonry layout
        $bricks.imagesLoaded(function()
        {
          // show bricks now that they're ready
          $bricks.animate({ opacity: 1 });
          $container.masonry('appended', $bricks, true);
        });
      });

      // Hide the pagination before infinite scroll does it
      $('#search-pagination').hide();
    <?php endif; ?>
  });
</script>
