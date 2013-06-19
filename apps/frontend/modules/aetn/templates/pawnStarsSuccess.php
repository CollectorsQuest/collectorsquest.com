<?php
/* @var $pager PropelModelPager */
?>

<div class="spacer-bottom-15">
  <?php
    echo link_to(cq_image_tag(
      'headlines/pawn_stars_2013_0730_PS_collect_quest_620x180.jpg',
      array('alt_title' => 'Check out items seen on Pawn Stars')
    ), 'http://www.history.com/shows/pawn-stars', array('target' => '_blank'));
  ?>
</div>

<p class="text-justify">
  In the only family-run pawn shop in Las Vegas, three generations of men
  from the Harrison family buy and sell collectible, unusual and historically
  significant items on HISTORY’s <strong><i>PAWN STARS</i></strong><sup>&reg;</sup>.
  Their customers are carrying on a centuries-old practice: pawning or selling
  their possessions to make a quick buck. What would you be willing to gamble
  on these items from the show?
  <br><br>
  <small>
    <i>*&nbsp;Pawn Stars,</i>&nbsp;HISTORY and the History “H”
    logo are the trademarks of A&amp;E Television Networks, LLC.
  </small>
</p>

<?php
  cq_page_title(
    'Collectibles Seen on <strong><i>PAWN STARS</i></strong>', null,
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
