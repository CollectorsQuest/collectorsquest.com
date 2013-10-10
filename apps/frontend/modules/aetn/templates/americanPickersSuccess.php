<?php
/* @var $pager PropelModelPager */
?>

<div class="spacer-bottom-15">
  <?php
    echo link_to(cq_image_tag(
      'headlines/2013-1009-H_AP_620x180_C1[1].jpg',
      array('alt_title' => 'Check out items seen on American Pickers')
    ), 'http://www.history.com/shows/american-pickers', array('target' => '_blank'));
  ?>
</div>

<p class="text-justify">
  Part sleuths, part antiques experts, and part cultural historians,
  professional ‘pickers’ Mike Wolfe and Frank Fritz’s adventures in
  <strong><i>AMERICAN PICKERS</i></strong><sup>&reg;</sup> on HISTORY<sup>&reg;</sup>
  bring them to small towns across the country. Combing through memorabilia and
  artifacts and hoping to find treasures among the trash, they find their ‘gold’
  in items of all kinds, like the pieces featured below.
  <br><br>
  <small>
    <i>*&nbsp;American Pickers,</i>&nbsp;HISTORY and the History “H”
    logo are the trademarks of A&amp;E Television Networks, LLC.
  </small>
</p>

<?php
  cq_page_title(
    'Collectibles Seen on <strong><i>AMERICAN PICKERS</i></strong>', null,
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
