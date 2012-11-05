<?php
/**
 * @var  $title                  string
 * @var  $collectibles_for_sale  CollectibleForSale[]
 * @var  $height                 stdClass
 * @var  $limit                  integer
 * @var  $collector              Collector
 */

$_height = 0;
?>

<?php
  if (!isset($collector))
  {
    $link = cq_link_to(
      'Explore Market &raquo;',
      '@marketplace', array('class' => 'text-v-middle link-align')
    );
    cq_sidebar_title($title, $link, array('left' => 7, 'right' => 5));
  }
  else
  {
    $link = link_to(
      'See all &raquo;',
      'collectibles_for_sale_by_collector', $collector,
      array('class' => 'text-v-middle link-align')
    );
    cq_sidebar_title($title, $link, array('left' => 9, 'right' => 3));
  }

  $_height -= 63;
?>

<div id="items-for-sale-sidebar">
  <div class="row thumbnails">
    <?php
      for ($i = 0; $i < intval($collectibles_for_sale->count() / 2) * 2; $i++)
      {
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square_small',
          array('collectible_for_sale' => $collectibles_for_sale[$i], 'i' => $i)
        );
      }
    ?>
  </div>
</div>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    // one row is 142px in height, fits 2 collectibles
    $_height -= 142 * round($limit / 2);
    $height->value -= abs($_height);
  }
?>
