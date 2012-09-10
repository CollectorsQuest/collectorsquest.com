<?php
/**
 * @var  $form  CollectibleForSaleBuyForm
 * @var  $collectible  Collectible
 * @var  $collectible_for_sale  CollectibleForSale
 * @var  $sf_user  cqFrontendUser
 * @var  $height  stdClass
 * @var  $brand string
 */

  $brand = $sf_user->getFlash('brand', null, true, 'internal');
?>

<?php
  // if the collectible is part of aent collection
  if(isset($brand)):
?>
  <div class="banner-sidebar-top">
    <?php
      if ($brand === 'American Pickers')
      {
        cq_dart_slot('300x250', 'collections', 'americanpickers', 'sidebar');
      }
      else if ($brand === 'Pawn Stars')
      {
        cq_dart_slot('300x250', 'collections', 'pawnstars', 'sidebar');
      }
    ?>
  </div>


  <?php if ($brand === 'Pawn Stars'): ?>
  <?php /*
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_storage_wars', true); ?>" title="Check out items seen on Storage Wars">
      <img src="/images/headlines/storage-wars-banner.jpg" alt="">
        <span>
          Check out items seen on Storage Wars
        </span>
    </a>
  </div>
  */ ?>
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_american_pickers', true); ?>" title="Check out items seen on American Pickers">
      <img src="/images/headlines/american-pickers-banner.jpg" alt="Check out items seen on American Pickers">
    </a>
  </div>
  <?php elseif ($brand === 'American Pickers'): ?>
  <?php /*
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_storage_wars', true); ?>" title="Check out items seen on Storage Wars">
      <img src="/images/headlines/storage-wars-banner.jpg" alt="Check out items seen on Storage Wars">
    </a>
  </div>
  */ ?>
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
      <img src="/images/headlines/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
    </a>
  </div>
  <?php endif; ?>

  <?php $height->value -= 375; ?>

<?php else: ?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array(
      'collector' => $collectible->getCollector(),
      'collectible' => $collectible,
      'limit' => 0, 'message' => true, 'height' => &$height
    )
  );
?>

<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collectible' => $collectible, 'limit' => 3,
      'fallback' => 'random', 'height' => &$height
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collectible' => $collectible, 'fallback' => 'random', 'height' => &$height)
  );
?>
