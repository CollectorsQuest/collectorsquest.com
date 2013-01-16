<?php
/* @var $pager cqPropelModelPager */
/* @var $rand  integer */
?>

<div id="collectibles" class="row thumbnails" style="margin-left: 0;">
<?php
  /* @var $collectible Collectible */
  /* @var $pager       PropelModelPager */
  foreach ($pager->getResults() as $i => $collectible)
  {
    // special case to include holiday promo banner
/*    if ($rand == $i)
    {
      include_partial('marketplace/partials/holidayMarketBuyPackagePromo_220x170');
    }*/

    // set the link to open modal dialog
    $ajax_url = url_for('ajax_marketplace',
      array(
        'section' => 'collectible',
        'page' => 'forSale',
        'id' => $collectible->getId()
      )
    );

    $url = url_for('collectible_by_slug', $collectible);

    include_partial(
      'marketplace/collectible_for_sale_masonry_view_big',
      array(
        'collectible_for_sale' => $collectible->getCollectibleForSale(),
        'url' => $url,
        'link_parameters' => array(
            'class' => 'open-modal zoom-zone',
            'data-ajax-url' => $ajax_url,
        ),
      )
    );
  }
?>
</div>

<?php if ($pager->getNbResults() === 0): ?>
<div style="margin: 15px 20px;">
  <i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F; margin-top: 6px;">&nbsp;</i>
  Sorry! We can't find anything that matches your search.
  Try a broader search, or browse around for other neat stuff.<br/>
  (Or you can <?= link_to('sell something of your own', '@mycq_collections'); ?> on the site!)
</div>
<?php endif; ?>

<?php if ($pager->getPage() === 1): ?>

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

      <?php if ($pager->haveToPaginate()): ?>

        var $form = $('#form-holiday-collectibles');

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

            return path.match(/^(.*?[\?|&]p=)2(.*?$)/).slice(1);
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
<?php endif; ?>
