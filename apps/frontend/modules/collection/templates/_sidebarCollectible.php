<?php
/**
 * @var  $collectible  Collectible
 * @var  $sf_user      cqFrontendUser
 * @var  $height       stdClass
 * @var  $aetn_show    array
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
  <?php elseif ($aetn_show['id'] === 'american_pickers'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/pawn-stars-banner.jpg'), '@aetn_pawn_stars',
          array('alt_title' => 'Check out items seen on Pawn Stars')
        );
      ?>
    </div>
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

  <?php endif; ?>

  <?php $height->value -= 375; ?>

<?php else: ?>

  <?php
    include_component(
      '_sidebar', 'widgetCollector',
      array(
        'collector' => $collectible->getCollector(),
        'collectible' => $collectible,
        'limit' => 0, 'message' => true, 'height' => &$height
      )
    );
  ?>

<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collectible' => $collectible, 'limit' => 3,
      'fallback' => 'random', 'height' => &$height
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collectible' => $collectible, 'fallback' => 'random', 'height' => &$height)
  );
?>
