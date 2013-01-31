<?php
/* @var $collection Collection */
/* @var $sf_request   cqWebRequest */
?>

<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'storagewars', 'sidebar') ?>
</div>

<?php // include_partial('marketplace/partials/holidayMarketPromo_300x90'); ?>

<?php
  if (!$sf_request->isMobileLayout()):
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
    include_partial('marketplace/partials/marketBecomeSellerPromo_300x250');
  endif;
?>

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
