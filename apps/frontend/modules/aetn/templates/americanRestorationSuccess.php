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
      'headlines/2012-0777_AR_620x180.jpg',
      array('alt_title' => 'Check out items seen on American Restoration')
    ), 'http://www.history.com/shows/american-restoration', array('target' => '_blank'));
  ?>
</div>

<p class="text-justify">
  <?= $collection->getDescription('html'); ?>
  <br>
  <small>
    <i>*&nbsp;American Restoration,</i>&nbsp;HISTORY and the History “H”
    logo are the trademarks of A&amp;E Television Networks, LLC.
  </small>
</p>

<?php
  cq_page_title(
    'Collectibles Seen on <strong><i>AMERICAN RESTORATION</i></strong>™', null,
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
