<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'americanpickers', 'sidebar') ?>
</div>

<?php /*
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars'); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/headlines/storage-wars-banner.jpg" alt="Check out items seen on Storage Wars">
    <span>
      Check out items seen on Storage Wars
    </span>
  </a>
</div>
*/ ?>

<?php include_partial('marketplace/holidayMarketPromo_300x90'); ?>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/headlines/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
<?php /*
    <span>
      Check out items seen on Pawn Stars
    </span>
 */ ?>
  </a>
</div>

<?php if(IceGateKeeper::open('aetn_american_restoration', 'page')): ?>
  <div class="banner-sidebar-promo-300-90">
    <a href="<?= url_for('@aetn_american_restoration', true); ?>" title="Check out items seen on American Restoration">
      <img src="/images/headlines/2012-0777_AR_300x90.jpg" alt="Check out items seen on American Restoration">
    </a>
  </div>
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collection' => $collection,
      'limit' => 6, 'fallback' => 'random'
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetMoreHistory'
  );
?>
