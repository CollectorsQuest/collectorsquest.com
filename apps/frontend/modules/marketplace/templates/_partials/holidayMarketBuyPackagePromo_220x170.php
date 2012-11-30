<?php
/* @var $class   string */
?>

<div class="span4 brick collectible_for_sale_grid_view_masonry_big promo-banner">
  <?php
    // display one of two random banners
    $rand = rand(1, 2);

    cq_ad_slot(
      cq_image_tag('headlines/20121018_220x170_banner-' . $rand . '.png',
        array(
          'width' => '222', 'height' => '175',
          'alt' => 'Sell you stuff in our market and make $$ for the holidays!'
        )
      ),
      '@seller_signup?ref=mp'
    );
  ?>
</div>
