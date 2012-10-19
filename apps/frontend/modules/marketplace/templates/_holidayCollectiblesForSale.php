<div id="collectibles" class="row thumbnails" style="margin-left: 0;">
<?php
  /** @var $collectible Collectible */
  foreach ($pager->getResults() as $i => $collectible)
  {
    // set the link to open modal dialog
    $link = link_to($collectible->getName(), 'ajax_marketplace',
      array(
        'section' => 'collectible',
        'page' => 'forSale',
        'id' => $collectible->getId()
      ),
      array('class' => 'target zoom-zone', 'onclick' => 'return false;')
    );

    include_partial(
      'marketplace/collectible_for_sale_masonry_view_big',
      array(
        'collectible_for_sale' => $collectible->getCollectibleForSale(),
        'link' => $link
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
  });
</script>
