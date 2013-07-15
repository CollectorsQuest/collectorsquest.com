<?php
  /* @var $aetn_show    array        */
  /* @var $collectible  Collectible  */
  /* @var $height       stdClass     */
  /* @var $sf_request   cqWebRequest */
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
  case 'american_pickers':
    if ($sf_request->isMobileLayout()):
      include_partial('aetn/partials/franksPicksPromo_620x67');
      include_partial('aetn/partials/americanRestorationPromo_620x67');
    else:
      include_partial('marketplace/partials/marketBecomeSellerPromo_300x250');
      include_partial('aetn/partials/americanRestorationPromo_300x90');
    endif;
    break;
  case 'picked_off':
    if ($sf_request->isMobileLayout()):
      include_partial('aetn/partials/franksPicksPromo_620x67');
      include_partial('aetn/partials/americanPickersPromo_620x67');
    else:
      include_partial('marketplace/partials/marketBecomeSellerPromo_300x250');
      include_partial('aetn/partials/pawnStarsPromo_300x90');
      include_partial('aetn/partials/americanPickersPromo_300x90');
    endif;
    break;
  case 'american_restoration':
    if ($sf_request->isMobileLayout()):
      include_partial('aetn/partials/franksPicksPromo_620x67');
      include_partial('aetn/partials/americanPickersPromo_620x67');
    else:
      include_partial('marketplace/partials/marketBecomeSellerPromo_300x250');
      include_partial('aetn/partials/americanPickersPromo_300x90');
    endif;
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

    if ($sf_request->isMobileLayout()):
      include_partial('aetn/partials/americanRestorationPromo_620x67');
    else:
      include_partial('aetn/partials/americanRestorationPromo_300x90');
    endif;
    break;
}

// height of add + 2 promo banners and all margins
$height->value -= 530;

if ($aetn_show['id'] != 'franks_picks')
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
