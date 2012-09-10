<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'pawnstars', 'sidebar') ?>
</div>

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
<?php /*
    <span>
      Check out items seen on American Pickers
    </span>
*/ ?>
  </a>
</div>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'limit' => 6, 'fallback' => 'random'
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetMoreHistory'
  );
?>
