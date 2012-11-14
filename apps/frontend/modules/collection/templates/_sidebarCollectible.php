<?php
/**
 * @var  $collectible      Collectible
 * @var  $sf_user          cqFrontendUser
 * @var  $height           stdClass
 * @var  $aetn_show        array
 * @var  $ref_marketplace  boolean
 */
?>

<?php if (isset($aetn_show)): ?>
  <div class="banner-sidebar-top">
    <?php cq_dart_slot('300x250', 'collections', str_replace('_', '', $aetn_show['id']), 'sidebar'); ?>
  </div>

  <?php
    if ($aetn_show['id'] === 'pawn_stars') :
      include_partial('aetn/partials/americanPickersPromo_300x90');
      include_partial('aetn/partials/americanRestorationPromo_300x90');

    elseif ($aetn_show['id'] === 'american_pickers') :
      include_partial('aetn/partials/pawnStarsPromo_300x90');
      include_partial('aetn/partials/americanRestorationPromo_300x90');

    elseif ($aetn_show['id'] === 'picked_off'):
      include_partial('aetn/partials/pawnStarsPromo_300x90');
      include_partial('aetn/partials/americanPickersPromo_300x90');

    elseif ($aetn_show['id'] === 'american_restoration'):
      include_partial('aetn/partials/pawnStarsPromo_300x90');
      include_partial('aetn/partials/americanPickersPromo_300x90');

    elseif ($aetn_show['id'] === 'franks_picks'):
      include_partial('aetn/partials/pawnStarsPromo_300x90');
      include_partial('aetn/partials/americanPickersPromo_300x90');

      if ($collectible->isForSale())
      {
        include_component(
          '_sidebar', 'widgetCollectiblesForSale',
          array(
            'collectible' => $collectible, 'limit' => 4,
            'fallback' => 'random', 'height' => &$height
          )
        );
      }
    endif;
  ?>

  <?php
    // height of add + 2 promo banners and all margins
    $height->value -= 530;
  ?>

  <?php
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
  ?>

  <?php include_component('_sidebar', 'widgetMoreHistory', array('height' => &$height)); ?>

<?php else: ?>

  <?php

    include_component(
      '_sidebar', 'widgetManageCollectible',
      array('collectible' => $collectible, 'height' => &$height)
    );

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

    if ($ref_marketplace && $collectible->isForSale() &&
      $collectible->getCollector()->countFrontendCollectionCollectiblesForSale() > 1)
    {
      include_component(
        '_sidebar', 'widgetCollectiblesForSale',
        array(
          'collector' => $collectible->getCollector(),
          'exclude_collectible_ids' => array($collectible->getId()), 'limit' => 4,
          'title' => 'More from this Seller', 'height' => &$height
        )
      );
    }
    else
    {
      include_component(
        '_sidebar', 'widgetCollectionCollectibles',
        array('collectible' => $collectible, 'height' => &$height)
      );
    }

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

    include_component(
      '_sidebar', 'widgetTags',
      array('collectible' => $collectible, 'height' => &$height)
    );

    if (!$collectible->isForSale())
    {
      include_component(
        '_sidebar', 'widgetCollections',
        array('collectible' => $collectible, 'fallback' => 'random', 'height' => &$height)
      );
    }
  ?>

<?php endif; ?>
