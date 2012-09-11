<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'pickedoff', 'sidebar') ?>
</div>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/headlines/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
  </a>
</div>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_american_pickers', true); ?>" title="Check out items seen on American Pickers">
    <img src="/images/headlines/american-pickers-banner.jpg" alt="Check out items seen on American Pickers">
  </a>
</div>

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
