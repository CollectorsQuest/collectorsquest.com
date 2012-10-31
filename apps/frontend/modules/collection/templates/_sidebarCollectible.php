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

  <?php if ($aetn_show['id'] === 'pawn_stars'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/american-pickers-banner.jpg'), '@aetn_american_pickers',
          array('alt_title' => 'Check out items seen on American Pickers')
        );
      ?>
    </div>

    <?php if (IceGateKeeper::open('aetn_american_restoration', 'page')): ?>
      <div class="banner-sidebar-promo-300-90">
        <a href="<?= url_for('@aetn_american_restoration', true); ?>"
           title="Check out items seen on American Restoration">
          <img src="/images/headlines/2012-0777_AR_300x90.jpg" alt="Check out items seen on American Restoration">
        </a>
      </div>
    <?php endif; ?>
  <?php elseif ($aetn_show['id'] === 'american_pickers'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/pawn-stars-banner.jpg'), '@aetn_pawn_stars',
          array('alt_title' => 'Check out items seen on Pawn Stars')
        );
      ?>
    </div>

    <?php if (IceGateKeeper::open('aetn_american_restoration', 'page')): ?>
      <div class="banner-sidebar-promo-300-90">
        <a href="<?= url_for('@aetn_american_restoration', true); ?>"
           title="Before & after as seen on American Restoration">
          <img src="/images/headlines/2012-0777_AR_300x90.jpg" alt="Before & after as seen on American Restoration">
        </a>
      </div>
    <?php endif; ?>
  <?php elseif ($aetn_show['id'] === 'picked_off'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/american-pickers-banner.jpg'), '@aetn_american_pickers',
          array('alt_title' => 'Check out items seen on American Pickers')
        );
      ?>
    </div>

    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/pawn-stars-banner.jpg'), '@aetn_pawn_stars',
          array('alt_title' => 'Check out items seen on Pawn Stars')
        );
      ?>
    </div>
    <?php $height->value -= 120; ?>
  <?php elseif ($aetn_show['id'] === 'american_restoration'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/pawn-stars-banner.jpg'), '@aetn_pawn_stars',
          array('alt_title' => 'Check out items seen on Pawn Stars')
        );
      ?>
    </div>

    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/american-pickers-banner.jpg'), '@aetn_american_pickers',
          array('alt_title' => 'Check out items seen on American Pickers')
        );
      ?>
    </div>
    <?php $height->value -= 120; ?>
  <?php endif; ?>

  <?php $height->value -= 375; ?>

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

    if ($ref_marketplace && $collectible->isForSale())
    {
      include_component(
        '_sidebar', 'widgetCollectiblesForSale',
        array(
          'collector' => $collectible->getCollector(),
          'exclude_collectible_ids' => array($collectible->getId()), 'limit' => 4,
          'title' => 'Other Items from this Seller',
          'fallback' => 'random', 'height' => &$height
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
