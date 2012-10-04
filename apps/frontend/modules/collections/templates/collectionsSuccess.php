<?php cq_page_title('Latest Collections', '&nbsp;'); ?>

<br/>
<div class="row">
  <div id="collections" class="row-content">
    <?php
      /** @var $collection Collection */
      foreach ($pager->getResults() as $i => $collection)
      {
        include_partial(
          'collection/collection_grid_view_square_small',
          array('collection' => $collection, 'i' => $collection->getId())
        );
      }
    ?>
  </div>
</div>

<div class="row-fluid text-center">
<?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
?>
</div>
