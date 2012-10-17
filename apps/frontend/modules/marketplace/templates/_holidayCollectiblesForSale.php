<div id="collectibles" class="row thumbnails" style="margin-left: -3px;">
<?php
  /** @var $collectible Collectible */
  foreach ($pager->getResults() as $i => $collectible)
  {
    include_partial(
      'marketplace/collectible_for_sale_grid_view_masonry_big',
      array('collectible_for_sale' => $collectible->getCollectibleForSale())
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
      'height' => &$height_main_div,
      'options' => array(
        'id' => 'collectibles-pagination',
        'show_all' => true,
        'url' => url_for('@ajax_marketplace?section=component&page=holidayCollectiblesForSale'),
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
<?php elseif ($pager->getPage() > 1): ?>
<!--<div class="well clear-both" style="margin: 0; margin-left: 13px; padding: 10px;">-->
<!--  <i class="icon icon-search"></i>&nbsp;-->
<!--  --><?//= link_to('Not finding what you are looking for? Click here to find it on our search page!', $url); ?>
<!--</div>-->
<br>
<?php endif; ?>

<script>
  $(document).ready(function()
  {
    var $form = $('#form-discover-collectibles');

    window.cq.settings = $.extend(true, {}, window.cq.settings, {
      masonry: {
        loading_image: '<?= image_path('frontend/progress.gif'); ?>',
        loading_text: 'Loading more results...'
      }
    });

    $('#collectibles').infinitescroll(
    {
      navSelector:'#collectibles-pagination',
      nextSelector:'#collectibles-pagination li.next a',
      itemSelector:'#collectibles .span3',
      loading:{
        msgText:'Loading more collectibles...',
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
    function () {
      $('.collectible_grid_view').mosaic({
        animation:'slide'
      });
    });

    $("a.target").bigTarget({
      hoverClass: 'over',
      clickZone : 'div.link'
    });
  });
</script>
