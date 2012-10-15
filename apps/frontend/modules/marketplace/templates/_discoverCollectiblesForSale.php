<div id="collectibles" class="row thumbnails">
<?php
  /** @var $collectible Collectible */
  foreach ($pager->getResults() as $i => $collectible)
  {
    include_partial(
      'marketplace/collectible_for_sale_grid_view_square_small',
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
        'url' => url_for('@ajax_marketplace?section=component&page=discoverCollectiblesForSale'),
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
<?php elseif ($pager->haveToPaginate()): ?>
<div class="see-more-under-image-set" style="padding: 0; margin-left: 13px;">
  <button class="btn btn-small see-more-full" id="seemore-explore-collectibles">
    See more
  </button>
</div>
<?php endif; ?>

<script>
  $(document).ready(function()
  {
    var $form = $('#form-discover-collectibles');

    $('#seemore-explore-collectibles').click(function()
    {
      var $button = $(this);
      $button.hide();

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

      // force infinite scroll load
      $('#collectibles').infinitescroll('retrieve');
    });

    $("a.target").bigTarget({
      hoverClass: 'over',
      clickZone : 'div.link'
    });
  });
</script>
