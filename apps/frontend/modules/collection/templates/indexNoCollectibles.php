<?php
/**
 * @var cqUser      $sf_user
 * @var Collection  $collection
 */
?>

<?= cq_page_title($collection, 'by '. link_to_collector($collection->getCollector(), 'text')); ?>

<i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F;"></i>
<?php
if ($sf_user->isOwnerOf($collection))
{
  if ($collection instanceof CollectionDropbox)
  {
    echo __('Your Dropbox is currently empty.'), '<br>';
    echo __('Please, use the menu on the right and click on <b>"+ Add Collectibles"</b> and upload all pictures of your collectibles.');
  }
  else
  {
    echo __('Your collection is currently empty.'), '<br>';
    echo __('Please, use the menu on the right and click on <b>"+ Add Collectibles"</b> and upload all pictures of your collectibles.');
  }
}
else
{
  if ($collection instanceof CollectionDropbox)
  {
    echo __('We are sorry but the Dropbox of %DisplayName% is currently empty.', array('%DisplayName%' => $collector->getDisplayName()));
    echo '&nbsp;';
    echo __('If you are interested, you can use <b>"Message Collector"</b> to the right and encourage the collector to upload pictures of their collectibles.');
  }
  else
  {
    echo __('We are sorry but this collection is currently empty.');
    echo '&nbsp;';
    echo __('If you are interested, you can use <b>"Message Collector"</b> to the right and encourage the collector to upload pictures of their collectibles.');
  }
}
?>

<br clear="all"><br>
<?php if (!empty($collections)): ?>
<?php cq_section_title(__('Here are some more interesting collections:')); ?>
<br>
<?php
  foreach ($collections as $i => $collection)
  {
    // Show the collection (in grid, list or hybrid view)
    include_partial(
      'collections/grid_view_collection',
      array('collection' => $collection, 'i' => $i)
    );

    echo (($i + 1) % 3 == 0) ? '<br clear="all">' : null;
  }
  ?>
<?php endif; ?>
