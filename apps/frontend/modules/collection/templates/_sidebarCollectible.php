<?php
/**
 * @var  $collectible  Collectible
 * @var  $sf_user      cqFrontendUser
 * @var  $height       stdClass
 * @var  $brand        string
 */
?>

<?php
  // if the collectible is part of aent Collection
  if(isset($brand)):
?>
  <div class="banner-sidebar-top">
    <?php
      if ($brand === 'American Pickers')
      {
        cq_dart_slot('300x250', 'collections', 'americanpickers', 'sidebar');
      }
      else if ($brand === 'Pawn Stars')
      {
        cq_dart_slot('300x250', 'collections', 'pawnstars', 'sidebar');
      }
      else if ($brand === 'Picked Off')
      {
        cq_dart_slot('300x250', 'collections', 'pickedoff', 'sidebar');
      }
    ?>
  </div>

  <?php if ($brand === 'Pawn Stars'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/american-pickers-banner.jpg'), '@aetn_american_pickers',
          array('alt_title' => 'Check out items seen on American Pickers')
        );
      ?>
    </div>
  <?php elseif ($brand === 'American Pickers'): ?>
    <div class="banner-sidebar-promo-300-90">
      <?php
        echo cq_link_to(
          cq_image_tag('headlines/pawn-stars-banner.jpg'), '@aetn_pawn_stars',
          array('alt_title' => 'Check out items seen on Pawn Stars')
        );
      ?>
    </div>
  <?php elseif ($brand === 'Picked Off'): ?>

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
