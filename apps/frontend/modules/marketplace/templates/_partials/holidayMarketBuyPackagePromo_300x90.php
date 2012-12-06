<?php
  /* @var $sf_user cqFrontendUser */
  /* @var $class   string */
?>

<?php if ($sf_user->isAuthenticated() && $sf_user->getSeller() && !$sf_user->getSeller()->hasPackageCredits()): ?>
<div class="banner-sidebar-promo-300-90">
  <?php
    // display one of two random banners
    $rand = rand(0, 1);
    $rand == 0 ? $banner = '20121018_market_its_easy_300x90.png' : $banner = '20121018_market_sell_it_300x90.png';

    // check if we have variable class set
    isset($class) ?: $class = '';

    cq_ad_slot(
      cq_image_tag('headlines/' . $banner,
        array(
          'width' => '300', 'height' => '90', 'class' => $class,
          'alt' => 'Sell you stuff in our market and make $$ for the holidays!'
        )
      ),
      '@seller_packages'
    );

    if (isset($height))
    {
      $height->value -= 120;
    }
  ?>
</div>
<?php endif; ?>


