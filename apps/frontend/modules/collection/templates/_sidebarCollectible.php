<?php
/* @var  $collectible      Collectible     */
/* @var  $sf_user          cqFrontendUser  */
/* @var  $height           stdClass        */
/* @var  $aetn_show        array           */
/* @var  $ref_marketplace  boolean         */
/* @var $sf_request   cqWebRequest         */
/* @var $black_history_collectible_ids   array    */
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

    echo '<div class="wrapper">';

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

    echo '</div>';

    if ($sf_request->isMobileLayout())
    {
      // include_partial('aetn/partials/franksPicksPromo_620x67');
    }
    else if (in_array($collectible->getId(), $black_history_collectible_ids))
    {
      echo link_to(
        cq_image_tag('headlines/BlackHistory300x150.jpg',
          array(
            'size' => '300x150', 'class' => 'spacer-top-15',
            'alt' => 'African-American history is filled with the tales of inspiring individuals'
          )
        ),
        'http://www.biography.com/people/groups/black-history',
        array('target' => '_blank')
      );
      $height->value -= 165;
    }
    else
    {
      include_partial('marketplace/partials/marketBecomeSellerPromo_300x250', array('class' => 'spacer-top-15', 'height' => $height));
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
