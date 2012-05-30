<div class="spacer-bottom-15">
  <img src="/images/banners/2012-0420_AP_Promo_Space_620x180_FIN.jpg" alt="Check out items seen on American Pickers">
</div>

<p>
  Part sleuths, part antiques experts, and part cultural historians –
  Mike Wolfe and Frank Fritz’s adventures in AMERICAN PICKERS® on HISTORY®
  bring them to small towns across the country in search of weird and
  wonderful Americana. As professional “pickers,” these childhood buddies
  comb through memorabilia and artifacts, hoping to find treasures among the trash.
  Here are just a few treasures they’ve found along the way.
</p>

<?php cq_page_title('As Seen on American Pickers', null, array('class' => 'row-fluid header-bar spacer-bottom-15')); ?>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    /** @var $collectibles Collectible[] */
    foreach ($collectibles as $i => $collectible)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_grid_view_square',
        array('collectible' => $collectible, 'i' => (int) $i)
      );
    }
    ?>
  </div>
</div>

<?php
  $link = link_to('See all items for sale  &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
  cq_section_title('Featured Items For Sale', $link);
?>

<div id="items-for-sale">
  <div class="row thumbnails">
    <?php
    /** @var $collectibles_for_sale CollectibleForSale[] */
    foreach ($collectibles_for_sale as $i => $collectible_for_sale)
    {
      include_partial(
        'marketplace/collectible_for_sale_grid_view_square_small',
        array('collectible_for_sale' => $collectible_for_sale, 'i' => $i)
      );
    }
    ?>
  </div>
</div>
