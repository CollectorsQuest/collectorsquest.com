<?php
/* @var $pager             cqPropelModelPager */
/* @var $infinite_scroll   boolean            */
/* @var $collectibles_2x2  array              */
/* @var $collectibles_1x2  array              */
/* @var $collectibles_2x1  array              */
?>

<div id="collectibles" class="row-content">
  <?php
  foreach ($pager->getResults() as $i => $collectible)
  {
    /* @var $collectible Collectible */
    $id = $collectible->getId();

    // which partial we want to show the Collectible with
    $partial = '';
    if (in_array($id, $collectibles_2x1))
    {
      $partial = 'wide';
    }
    else if (in_array($id, $collectibles_1x2))
    {
      $partial = 'tall';
    }
    else if (in_array($id, $collectibles_2x2))
    {
      $partial = 'square_big';
    }
    else
    {
      $partial = 'square_small';
    }

    include_partial(
      'collection/collectible_grid_view_' . $partial,
      array(
        'collectible' => $collectible, 'i' => (int) $i
      )
    );
  }
  ?>
</div>

<?php if ($infinite_scroll == true && $pager->getPage() === 1): ?>
<div class="row-fluid text-center">
  <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-pagination',
          'url' => url_for('@ajax_misc?section=component&page=wordPressFeaturedItemsGrid'),
          'show_all' => true,
          'page_param' => 'page',
        )
      )
    );
  ?>
</div>

<script>
  $(document).ready(function()
  {
    var $container = $('#collectibles');

    $container.imagesLoaded(function() {
      $container.masonry({
        itemSelector : '.collectible_grid_view_square_small',
        columnWidth : 140, gutterWidth: 18
      });
    });

    $container.infinitescroll(
    {
      navSelector:'#collectibles-pagination',
      nextSelector:'#collectibles-pagination li.next a',
      itemSelector:'#collectibles .span3',
      loading:{
        msgText:'',
        finishedMsg:'No more pages to load.',
        img:'<?= image_path('frontend/progress.gif'); ?>'
      },
      state: {
        curPage: 2
      },
      bufferPx:150
    },
    // trigger Masonry as a callback
    function(selector) {
      // hide new bricks while they are loading
      var $bricks = $(selector).css({opacity: 0});

      // ensure that images load before adding to masonry layout
      $bricks.imagesLoaded(function() {
        // show bricks now that they're ready
        $bricks.animate({opacity: 1});
        $container.masonry('appended', $bricks, true);
      });
    });
  });
</script>
<?php endif; ?>
