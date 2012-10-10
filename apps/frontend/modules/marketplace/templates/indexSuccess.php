<?php
/**
 * @var $wp_post wpPost
 * @var $collectibles_for_sale CollectibleForSale[]
 * @var $collectibles_for_sale_text array
 * @var $sf_user  cqFrontendUser
 */
$height_main_div = new stdClass;
$height_main_div->value = 1300;

$sf_user->setFlash('height_main_div', $height_main_div, false, 'internal');
?>

<?php cq_page_title('Shop Antique, Collectible and Vintage Items'); ?>

<?php if (isset($wp_post) && $wp_post instanceof wpPost): ?>
<div class="row-fluid" id="marketplace-spotlight">
  <h2 class="spotlight-title Chivo webfont">
    <?= $wp_post->getPostTitle() ?>
  </h2>
  <?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
  <div class="span4 link">
    <div class="thumbnail">
      <div class="spotlight-thumb">
        <?php
          echo image_tag_collectible(
            $collectible_for_sale->getCollectible(), '190x190',
            array('width' => 180, 'height' => 180)
          );
        ?>
        <?php if (isset($collectibles_for_sale_text[$i])): ?>
          <span class="blue-label"><?= $collectibles_for_sale_text[$i]; ?></span>
        <?php endif; ?>
      </div>
      <div class="spotlight-text">
        <h4><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target')); ?></h4>
        <p><?= $collectible_for_sale->getCollectible()->getDescription('stripped', 120); ?></p>
      </div>
      <div class="spotlight-price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>&nbsp;&nbsp;
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
<div class="banners-620 spacer-top-20">
  <?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(image_tag('headlines/show_and_sell_red_635x111.png'), '@seller_signup');
  }
  elseif ($sf_user->getSeller() && !$sf_user->getSeller()->hasPackageCredits())
  {
    echo link_to(image_tag('headlines/show_and_sell_red_635x111_user.png'), '@seller_packages');
  }
  // avoid having too much blank space when no banner is displayed
  else
  {
    echo '<style type="text/css">#main { min-height: 400px; } #content { padding-bottom: 0; }</style>';
  }
  ?>
</div>
<?php endif; ?>
