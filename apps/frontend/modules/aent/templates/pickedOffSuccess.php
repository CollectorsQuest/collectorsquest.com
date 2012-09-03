<div class="spacer-bottom-15">
  <?php
  /**
   * we should replace with proper 'Picked Off' image
   *
     <img src="/images/headlines/2012-0420_PS_Promo_Space_620x180_FIN.jpg" alt="">
   */
  ?>
</div>

<p class="text-justify">
  Text about HISTORY’s <strong><i>PICKED OFF</i></strong><sup>&reg;</sup>.
</p>

<?php
  cq_page_title(
    'Collectibles Seen on <strong><i>Picked Off</i></strong>', null,
    array('class' => 'row-fluid header-bar spacer-bottom-15')
  );
?>

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
  if (count($collectibles_for_sale) > 0):
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

<?php endif; ?>
