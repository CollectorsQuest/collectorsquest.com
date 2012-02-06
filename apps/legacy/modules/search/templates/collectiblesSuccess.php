<br clear="all"/>

<?php if ($collectibles): ?>
<div id="search-collectibles">
  <?php
    foreach ($collectibles as $i => $collectible)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/grid_view_collectible',
        array(
          'collectible' => $collectible,
          'culture' => $sf_user->getCulture(), 'i' => $i
        )
      );
    }
  ?>
</div>
<br clear="all"/>
<?php endif; ?>

<?php
  include_partial(
    'global/pager',
    array('pager' => $pager, 'options' => array('url' => '@search_collectibles?q='. $q))
  );
?>
