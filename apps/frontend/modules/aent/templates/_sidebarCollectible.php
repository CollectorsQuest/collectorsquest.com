<?php
/**
 * @var $brand string
 * @var $collectible Collectible
 */
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
<?php /**
<div class="banner-sidebar-promo-300-90">
  <?php
    echo cq_link_to(
      cq_image_tag('headlines/picked-off-banner.jpg'), '@aetn_picked_off',
      array('alt_title' => 'Check out items seen on Picked Off')
    );
  ?>
</div>
*/ ?>
<div class="banner-sidebar-promo-300-90">
  <?php
    echo cq_link_to(
      cq_image_tag('headlines/american-pickers-banner.jpg'), '@aetn_american_pickers',
      array('alt_title' => 'Check out items seen on American Pickers')
    );
  ?>
</div>
<?php elseif ($brand === 'American Pickers'): ?>
<?php /*
<div class="banner-sidebar-promo-300-90">
  <?php
    echo cq_link_to(
      cq_image_tag('headlines/picked-off-banner.jpg'), '@aetn_picked_off',
      array('alt_title' => 'Check out items seen on Picked Off')
    );
  ?>
</div>
*/ ?>
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
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>
