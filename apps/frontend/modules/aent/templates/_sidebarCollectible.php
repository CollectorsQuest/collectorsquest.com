<div class="banner-sidebar-top">
  <?php cq_ad_slot('300x250', 300, 250) ?>
</div>

<?php if ($brand === 'Pawn Stars'): ?>
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_storage_wars'); ?>" title="Check out items seen on Storage Wars">
      <img src="/images/banners/storage-wars-banner.jpg" alt="">
      <span>
        Check out items seen on Storage Wars
      </span>
    </a>
  </div>
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_american_pickers'); ?>" title="Check out items seen on American Pickers">
      <img src="/images/banners/american-pickers-banner.jpg" alt="">
      <span>
        Check out items seen on American Pickers
      </span>
    </a>
  </div>
<?php elseif ($brand === 'American Pickers'): ?>
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars'); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/banners/storage-wars-banner.jpg" alt="Check out items seen on Storage Wars">
    <span>
      Check out items seen on Storage Wars
    </span>
  </a>
</div>
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars'); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/banners/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
      <span>
        Check out items seen on Pawn Stars
      </span>
  </a>
</div>
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible)
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
    '_sidebar', 'widgetCollectiblesForSale',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>
