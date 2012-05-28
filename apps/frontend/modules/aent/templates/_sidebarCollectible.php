<?php
/**
 * @var $brand string
 * @var $collectible Collectible
 */
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

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible, 'limit' => 4)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible)
  );
?>

<?php if ($brand === 'Pawn Stars'): ?>
<?php /*
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars', true); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/banners/storage-wars-banner.jpg" alt="">
      <span>
        Check out items seen on Storage Wars
      </span>
  </a>
</div>
*/ ?>
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_american_pickers', true); ?>" title="Check out items seen on American Pickers">
    <img src="/images/banners/american-pickers-banner.jpg" alt="Check out items seen on American Pickers">
    <span>
      Check out items seen on American Pickers
    </span>
  </a>
</div>
<?php elseif ($brand === 'American Pickers'): ?>
<?php /*
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars', true); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/banners/storage-wars-banner.jpg" alt="Check out items seen on Storage Wars">
    <span>
      Check out items seen on Storage Wars
    </span>
  </a>
</div>
*/ ?>
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/banners/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
    <span>
      Check out items seen on Pawn Stars
    </span>
  </a>
</div>
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>
