<?php
/* @var $pager cqPropelModelPager */
?>

<div id="collectibles" class="row thumbnails" style="margin-left: 0;">
  <?php
    /* @var $collectible Collectible */
    /* @var $pager       PropelModelPager */
    foreach ($pager->getResults() as $i => $collectible)
    {
      include_partial(
        'marketplace/collectible_for_sale_masonry_view_big',
        array(
          'collectible_for_sale' => $collectible->getCollectibleForSale(),
          'url' => url_for_collectible($collectible),
          'link_parameters' => array('class' => 'target zoom-zone'),
          'show_sold' => true
        )
      );
    }
  ?>
</div>

<?php if ($pager->getPage() === 1): ?>

  <div class="row-fluid text-center hidden">
    <?php
      include_component(
        'global', 'pagination',
        array(
          'pager' => $pager,
          'options' => array(
            'id' => 'collectibles-pagination',
            'url' => url_for('@ajax_aetn?section=component&page=franksPicksCollectiblesForSale'),
            'show_all' => true,
            'page_param' => 'p',
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
        itemSelector : '.brick',
        columnWidth : 220, gutterWidth: 18
      });
    });

    $container.infinitescroll(
    {
      navSelector:'#collectibles-pagination',
      nextSelector:'#collectibles-pagination li.next a',
      itemSelector:'#collectibles .span4',
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
