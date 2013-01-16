<?php
  /* @var $collection Collection */
  /* @var $sf_request   cqWebRequest */
?>

<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'americanrestoration', 'sidebar') ?>
</div>

<?php
  if ($sf_request->isMobileLayout()):
    include_partial('aetn/partials/franksPicksPromo_620x67');
    include_partial('aetn/partials/pawnStarsPromo_620x67');
    include_partial('aetn/partials/americanPickersPromo_620x67');
  else:
    include_partial('aetn/partials/franksPicksPromo_300x90');
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
  endif;
?>

<?php // include_partial('marketplace/partials/holidayMarketPromo_300x90'); ?>

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
