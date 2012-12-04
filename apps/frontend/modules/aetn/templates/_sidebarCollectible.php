<?php
  /* @var $aetn_show    array       */
  /* @var $collectible  Collectible */
  /* @var $height       stdClass    */
?>

<?php if ($collectible->isWasForSale()): ?>
  <?php
    include_component(
      '_sidebar', 'widgetCollectibleBuy',
      array('collectible' => $collectible, 'height' => &$height)
    );

    if ('franks_picks' == $aetn_show['id'])
    {
      include_component(
        '_sidebar', 'widgetCollectorAmericanPickers',
        array(
          'collector' => $collectible->getCollector(),
          'collectible' => $collectible,
          'message' => true, 'height' => &$height
        )
      );
    }
    else
    {
      include_component(
        '_sidebar', 'widgetCollector',
        array(
          'collector' => $collectible->getCollector(),
          'collectible' => $collectible,
          'limit' => 0, 'message' => true, 'height' => &$height
        )
      );
    }
  ?>
<?php else: ?>
  <div class="banner-sidebar-top">
    <?php cq_dart_slot('300x250', 'collections', str_replace('_', '', $aetn_show['id']), 'sidebar'); ?>
  </div>
<?php endif; ?>

<?php

switch ($aetn_show['id'])
{
  case 'pawn_stars':
    include_partial('aetn/partials/franksPicksPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
    include_partial('aetn/partials/americanRestorationPromo_300x90');
    break;
  case 'american_pickers':
    include_partial('aetn/partials/franksPicksPromo_300x90');
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanRestorationPromo_300x90');
    break;
  case 'picked_off':
    include_partial('aetn/partials/franksPicksPromo_300x90');
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
    break;
  case 'american_restoration':
    include_partial('aetn/partials/franksPicksPromo_300x90');
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
    break;
  case 'franks_picks':

    include_component(
      '_sidebar', 'widgetCollectiblesForSale',
      array(
        'collector' => $collectible->getCollector(),
        'exclude_collectible_ids' => array($collectible->getId()), 'limit' => 4,
        'title' => 'More of Frank\'s Picks', 'height' => &$height
      )
    );

    echo '<div style="height: 20px;"></div>';

    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanRestorationPromo_300x90');
    break;
}

// height of add + 2 promo banners and all margins
$height->value -= 530;

if (cqGateKeeper::open('aetn_franks_picks', 'page') && $aetn_show['id'] != 'franks_picks')
{
  $height->value -= 120;
}

// we are already displaying this widget on Frank's Picks show
if (!$collectible->isForSale() && $aetn_show['id'] != 'franks_picks')
{
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collectible' => $collectible, 'limit' => 4,
      'fallback' => 'random', 'height' => &$height
    )
  );
}

if ($aetn_show['id'] !== 'franks_picks')
{
  include_component('_sidebar', 'widgetMoreHistory', array('height' => &$height));
}
