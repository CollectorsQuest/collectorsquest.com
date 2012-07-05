<?php
/**
 * @var $collectibles_for_sale CollectibleForSale[]
 */
?>

<?php if (count($collectibles_for_sale) > 0 ): ?>
<div id="items-for-sale" class="well spacer-top">
  <div class="row thumbnails">
    <?php
      /** @var $collectible Collectible */
      foreach ($collectibles_for_sale as $i => $collectible_for_sale)
      {
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square_small',
          array('collectible_for_sale' => $collectible_for_sale)
        );
      }
    ?>
  </div>
</div>
<?php endif; ?>
