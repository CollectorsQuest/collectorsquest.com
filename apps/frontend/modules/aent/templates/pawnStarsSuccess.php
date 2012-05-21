<div class="spacer-bottom-15">
  <img src="/images/banners/040412_promo_space_banner.jpg" alt="">
</div>
<?php cq_page_title('As seen on Pawn Stars'); ?>

<br/>
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
