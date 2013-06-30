<?php
/* @var $class   string */
?>

<div class="span3 brick collection_grid_view_square_small link">
  <?php
    // display a random banner
    $banners = sfConfig::get('app_random_banners_sell', array());
    $rand = array_rand($banners);

    cq_ad_slot(
      cq_image_tag('frontend/banners/sell/20130629_140x140_' . $banners[$rand],
        array(
          'width' => '140', 'height' => '140',
          'alt' => 'Sell you stuff in our market and make $$!'
        )
      ),
      '@seller_signup?ref=mp'
    );
  ?>
</div>
