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

    include_partial(
      'marketplace/collectible_for_sale_masonry_view_big',
      array(
        'collectible_for_sale' => $collectible->getCollectibleForSale(),
        'url' => $url,
        'link_parameters' => array('class' => 'target zoom-zone', 'onclick' => 'return false;')
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

<?php if ($pager->getNbResults() === 0): ?>
<div style="margin: 15px 20px;">
  <i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F;"></i>
  Sorry! We can't find anything that matches your search.
  Try a broader search, or browse around for other neat stuff.
  (Or you can <?= link_to('sell something of your own', '@mycq_collections'); ?> on the site!)
</div>
<?php endif; ?>

<script>
  $(document).ready(function()
  {
    var $container = $('#collectibles');
    var $form = $('#form-discover-collectibles');

    $container.imagesLoaded(function() {
      $container.masonry({
        itemSelector : '.brick',
        columnWidth : 220, gutterWidth: 18
      });
    });

    var zoom_zone = function(event)
    {
      event.preventDefault();

      var $a = $(this);
      var $div = $('<div></div>');

      $a.closest('.collectible_for_sale_grid_view_masonry_big').showLoading();
      $div.appendTo('body').load($(this).attr('href'), function()
      {
        $a.closest('.collectible_for_sale_grid_view_masonry_big').hideLoading();
        $('.modal', $div).modal('show');
      });

      return false;
    };

    <?php /* The click() does not work for new elements, on() does not work for current?!? */ ?>
    $('a.zoom-zone').click(zoom_zone);
    $('#items-for-sale').on('click', 'a.zoom-zone', zoom_zone);

    <?php if ($pager->haveToPaginate()): ?>
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
        pathParse: function(path, page) {
          // add the search params from the form
          path = path + '&' + $form.serialize();
          return path = path.match(/^(.*?)2(.*?$)/).slice(1);
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
    <?php endif; ?>

  });
</script>
