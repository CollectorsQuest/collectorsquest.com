<?php cq_ad_slot('300x250', 300, 250) ?>
<br>
<?php if ($brand === 'Pawn Stars'): ?>
  <img src="/images/banners/040412_pawnstars_sidebar_banner_02.jpg" alt="">
  <br><br>
  <img src="/images/banners/040412_pawnstars_sidebar_banner_03.jpg" alt="">
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible, 'limit' => 4)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>
