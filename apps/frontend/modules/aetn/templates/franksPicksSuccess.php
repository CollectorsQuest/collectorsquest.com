<?php

echo ice_image_tag_placeholder('939x180');
echo '<br/><br/>';

cq_page_title("Frank's Picks");

?>
<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <div id="collectibles" class="row thumbnails" style="margin-left: 0;">
  <?php
    /* @var $collectible Collectible */
    /* @var $pager       PropelModelPager */
    foreach ($pager->getResults() as $i => $collectible)
    {
      // set the link to open modal dialog
      $url = url_for('ajax_marketplace',
        array(
          'section' => 'collectible',
          'page' => 'forSale',
          'id' => $collectible->getId()
        )
      );
      $link_parameters = 'class="target zoom-zone" onclick="return false;"';

      include_partial(
        'marketplace/collectible_for_sale_masonry_view_big',
        array(
          'collectible_for_sale' => $collectible->getCollectibleForSale(),
          'url' => $url,
          'link_parameters' => $link_parameters
        )
      );
    }
  ?>
  </div>

  <div class="row-fluid text-center hidden">
    <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-pagination',
          'url' => url_for('@ajax_marketplace?section=component&page=holidayCollectiblesForSale'),
          'show_all' => true,
          'page_param' => 'p',
        )
      )
    );
    ?>
  </div>

  <?php if ($pager->haveToPaginate()): ?>
  <div class="see-more-under-image-set" style="padding: 0;">
    <button class="btn btn-small see-more-full" id="seemore-explore-collectibles">
      See more
    </button>
  </div>
  <?php endif; ?>
</div>

<script>
  $(document).ready(function()
  {
    var $container = $('#collectibles');

    $container.imagesLoaded(function() {
      $container.masonry({
        itemSelector : '.brick',
        columnWidth : 220, gutterWidth: 18,
        isAnimated: !Modernizr.csstransitions
      });
    });

    $('a.zoom-zone').click(function(e)
    {
      e.preventDefault();

      var $a = $(this);
      var $div = $('<div></div>');

      $a.closest('.collectible_for_sale_grid_view_masonry_big').showLoading();
      $div.appendTo('body').load($(this).attr('href'), function()
      {
        $a.closest('.collectible_for_sale_grid_view_masonry_big').hideLoading();
        $('.modal', $div).modal('show');
      });

      return false;
    });

    $('#seemore-explore-collectibles').click(function()
    {
      var $button = $(this);
      $button.hide();

      $container.infinitescroll(
        {
          navSelector:'#collectibles-pagination',
          nextSelector:'#collectibles-pagination li.next a',
          itemSelector:'#collectibles .span4',
          loading:{
            msgText:'Loading more collectibles...',
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

      // force infinite scroll load
      $container.infinitescroll('retrieve');
    });

  });
</script>
