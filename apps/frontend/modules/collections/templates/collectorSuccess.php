<?php
/**
 * @var  $collectors_question array
 *
 * @var  $collector   Collector
 * @var  $collection  Collection
 * @var  $pager       PropelModelPager
 */
cq_page_title(
  sprintf('Collections by %s', $collector->getDisplayName()),
  link_to('Back to Collector &raquo;', 'collector_by_slug', $collector)
);
?>
<br />
<div class="row" style="margin-left: -12px;">
  <div id="collections" class="row-content">
    <?php
    foreach ($pager->getResults() as $i => $collection)
    {
      include_partial(
        'collection/collection_grid_view_square_small',
        array(
          'collection' => $collection,
          'i'          => $i
        )
      );
    }
    ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
  <?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
  ?>
</div>
