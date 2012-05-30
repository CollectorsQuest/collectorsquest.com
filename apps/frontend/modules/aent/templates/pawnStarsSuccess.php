<div class="spacer-bottom-15">
  <img src="/images/banners/2012-0420_PS_Promo_Space_620x180_FIN.jpg" alt="">
</div>

<p>
  They’ve seen 3-cent nickels, clocks that kill, a historic Revolutionary War
  bond printed by Paul Revere, sunken treasure from the Spanish Empire, and so
  much more on HISTORY’s PAWN STARS®. In the only family-run pawn shop in Las Vegas,
  three generations of men from the Harrison family buy and sell collectible, unusual
  and historically significant items. Their customers, often as colorful as the
  merchandise, are carrying on a centuries-old practice: pawning or selling their
  possessions to make a quick buck.  What would you be willing to gamble on these
  items from the show?
</p>

<?php cq_page_title('As Seen on Pawn Stars', null, array('class' => 'row-fluid header-bar spacer-bottom-15')); ?>

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
