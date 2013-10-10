<?php
/**
 * @var $pager                  PropelModelPager
 * @var $collection             Collection
 * @var $collectibles_for_sale  Collectible[]
 */
?>

<div class="spacer-bottom-15">
  <?php
    echo link_to(cq_image_tag(
      'headlines/2013-0730_CQ_CountingCars_620x180_FIN.jpg',
      array('alt_title' => 'Check out items seen on Counting Cars')
    ), 'http://www.history.com/shows/counting-cars', array('target' => '_blank'));
  ?>
</div>

<p class="text-justify">
  <?= $collection->getDescription('html'); ?>
  <br>
  <small>
    HISTORY and the History “H” logo are the
    trademarks of A&amp;E Television Networks, LLC.
  </small>
</p>

<?php
  cq_page_title(
    'Collectibles Seen on <strong><i>COUNTING CARS</i></strong>', null,
    array('class' => 'row-fluid header-bar spacer-bottom-15')
  );
?>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
      /** @var $collectibles Collectible[] */
      foreach ($pager->getResults() as $i => $collectible)
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'collection/collectible_grid_view_square',
          array('collectible' => $collectible, 'i' => (int) $i)
        );
      }
    ?>
  </div>
</div>

<div class="row-fluid text-center">
  <?php include_component('global', 'pagination', array('pager' => $pager)); ?>
</div>
