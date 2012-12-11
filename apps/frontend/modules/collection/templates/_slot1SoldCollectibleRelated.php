<?php
/* @var $sf_user                   cqFrontendUser */
/* @var $collector                 Collector */
/* @var $collectibles_for_sale     CollectibleForSale[] */
/* @var $title                     string */
/* @var $display_store_link        boolean */
?>

<div class="slot_1_padding">
  <div id="items-for-sale" class="well spacer-top">
    <h2 style="text-align: center; line-height: 24px;">
      This item is already sold!<br/>
      <small><?= $title ?></small>
    </h2>
    <br/>
    <div class="row thumbnails">
      <?php
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
</div>
