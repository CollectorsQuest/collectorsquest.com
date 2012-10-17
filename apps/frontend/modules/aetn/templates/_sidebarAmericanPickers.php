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

<?php include_partial('marketplace/partials/holidayMarketPromo_300x90'); ?>

<?php include_partial('aetn/partials/pawnStarsPromo_300x90'); ?>

<?php include_partial('aetn/partials/americanRestorationPromo_300x90'); ?>

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
