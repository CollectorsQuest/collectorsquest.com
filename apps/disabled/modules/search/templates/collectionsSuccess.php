<br clear="all"/>

<?php if ($collections): ?>
<div id="search-collections">
  <?php
    foreach ($collections as $i => $collection)
    {
      // Show the collection (in grid, list or hybrid view)
      include_partial(
        'collections/grid_view_collection',
        array(
          'collection' => $collection,
          'culture' => $sf_user->getCulture(),
          'i' => $i
        )
      );

      echo (($i + 1) % 3 == 0) ? '<br clear="all">' : null;
    }
  ?>
</div>
<br clear="all"/>
<?php endif; ?>

<?php
  include_partial(
    'global/pager',
    array('pager' => $pager, 'options' => array('url' => '@search_collections?q='. $q))
  );
?>
