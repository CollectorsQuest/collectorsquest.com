<div id="collectibles" class="row thumbnails" style="margin-left: 0;">
<?php
  /** @var $collectible Collectible */
  foreach ($pager->getResults() as $i => $collectible)
  {
    include_partial(
      'marketplace/collectible_for_sale_masonry_view_big',
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

    $container.imagesLoaded(function() {
      $container.masonry({
        itemSelector : '.brick',
        columnWidth : 220, gutterWidth: 18,
        isAnimated: !Modernizr.csstransitions
      });
    });
  });
</script>
