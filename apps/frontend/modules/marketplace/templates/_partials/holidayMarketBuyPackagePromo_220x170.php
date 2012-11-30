<?php
/* @var $sf_user cqFrontendUser */
/* @var $class   string */
?>

<?php if ($sf_user->isAuthenticated() && $sf_user->getSeller() && !$sf_user->getSeller()->hasPackageCredits()): ?>
<div class="banner-sidebar-promo-300-90">
  <?php
  // display one of two random banners
  $rand = rand(1, 2);

  // check if we have variable class set
  isset($class) ?: $class = '';

  cq_ad_slot(
    cq_image_tag('headlines/20121018_220x170_banner-' . $rand,
      array(
        'width' => '220', 'height' => '170', 'class' => $class,
        'alt' => 'Sell you stuff in our market and make $$ for the holidays!'
      )
    ),
    '@seller_packages'
  );

  if (isset($height))
  {
    $height->value -= 170;
  }
  ?>
</div>
<?php endif; ?>
