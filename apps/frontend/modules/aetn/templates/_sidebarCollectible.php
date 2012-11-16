<?php if ($collectible->isForSale()): ?>
  <?php
    include_component(
      '_sidebar', 'widgetCollectibleBuy',
      array('collectible' => $collectible, 'height' => &$height)
    );

    include_component(
      '_sidebar', 'widgetCollector',
      array(
        'collector' => $collectible->getCollector(),
        'collectible' => $collectible,
        'limit' => 0, 'message' => true, 'height' => &$height
      )
    );
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
    include_partial('aetn/partials/americanPickersPromo_300x90');
    include_partial('aetn/partials/americanRestorationPromo_300x90');
    break;
  case 'american_pickers':
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanRestorationPromo_300x90');
    break;
  case 'picked_off':
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
    break;
  case 'american_restoration':
    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanPickersPromo_300x90');
    break;
  case 'franks_picks':

    include_component(
      '_sidebar', 'widgetCollectiblesForSale',
      array(
        'collector' => $collectible->getCollector(),
        'exclude_collectible_ids' => array($collectible->getId()), 'limit' => 4,
        'title' => 'More from this Seller', 'height' => &$height
      )
    );

    echo '<div style="height: 20px;"></div>';

    include_partial('aetn/partials/pawnStarsPromo_300x90');
    include_partial('aetn/partials/americanRestorationPromo_300x90');
    break;
}

// height of add + 2 promo banners and all margins
$height->value -= 530;

if (!$collectible->isForSale())
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