<?php
  /* @var $sf_user cqFrontendUser */
  /* @var $class   string */
?>

<?php if ($sf_user->isAuthenticated() && !$sf_user->getCollector()->getIsSeller()): ?>
<div class="banner-sidebar-promo-300-250">
  <?php
    // display one of two random banners
    $rand = rand(0, 1);
    $rand == 0 ? $banner = '2013-01-31-ITS_EASY_PURPLE_300x250.jpg' : $banner = '2013-01-31-SELL_IT_PURPLE_300x250.jpg';

    // check if we have variable class set
    isset($class) ?: $class = '';

    cq_ad_slot(
      cq_image_tag('headlines/' . $banner,
        array(
          'width' => '300', 'height' => '250', 'class' => $class,
          'alt' => 'It\'s easy to make some $$! Open a store and sell you stuff in our Market!'
        )
      ),
      '@seller_signup'
    );

    if (isset($height))
    {
      $height->value -= 280;
    }
  ?>
</div>
<?php endif; ?>


