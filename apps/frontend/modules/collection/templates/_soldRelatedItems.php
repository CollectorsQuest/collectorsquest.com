<?php
/* @var $sf_user                   cqFrontendUser */
/* @var $collector                 Collector */
/* @var $collectibles_for_sale     CollectibleForSale[] */
/* @var $title                     string */
/* @var $display_store_link        boolean */
?>

<div class="slot_1_padding">
  <?php
    if ($display_store_link)
    {
      $link = link_to('Visit the store &raquo;', 'seller_store', $collector, array('class' => 'text-v-middle link-align'));
    }
    else
    {
      $link = link_to('Visit our marketplace &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
    }

    cq_section_title($title, $link, $options = array('left'=> 9, 'right' => 3, 'style' => 'margin-top: 0px;'));
  ?>

  <div id="items-for-sale" class="well spacer-top">
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

