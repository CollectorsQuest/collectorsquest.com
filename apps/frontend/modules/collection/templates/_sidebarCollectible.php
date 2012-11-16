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

  <?php
    include_partial(
      'aetn/sidebarCollectible',
      array(
        'aetn_show' => $aetn_show,
        'collectible' => $collectible,
        'height' => &$height
      )
    );
  ?>

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
