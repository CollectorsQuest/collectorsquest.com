<?php
/* @var $class   string */
?>

<div class="span4 brick collectible_for_sale_grid_view_masonry_mobile brik link">
  <?php
    // display a random banner
    $banners = sfConfig::get('app_random_banners_sell', array());
    $rand = array_rand($banners);

    cq_ad_slot(
      cq_image_tag('frontend/banners/sell/20130629_194x153_' . $banners[$rand],
        array(
          'width' => '194', 'height' => '153',
          'alt' => 'Sell you stuff in our market and make $$!'
        )
      ),
      '@seller_signup?ref=mp'
    );
  ?>
</div>
