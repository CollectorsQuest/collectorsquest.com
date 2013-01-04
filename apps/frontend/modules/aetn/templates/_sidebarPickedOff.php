<?php
/* @var $collection Collection */
?>

<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'pickedoff', 'sidebar') ?>
</div>

<?php include_partial('aetn/partials/franksPicksPromo_300x90'); ?>

<?php include_partial('aetn/partials/pawnStarsPromo_300x90'); ?>

<?php include_partial('aetn/partials/americanPickersPromo_300x90'); ?>

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
