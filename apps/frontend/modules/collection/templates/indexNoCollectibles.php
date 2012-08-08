<?php
/**
 * @var  cqFrontendUser  $sf_user
 * @var  Collection      $collection
 */
?>

<?php cq_page_title($collection); ?>

<?php
if ($sf_user->isOwnerOf($collection))
{
  if ($collection instanceof CollectionDropbox)
  {
    echo '
    <div class="alert spacer-top-40">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <i class="icon-exclamation-sign alert-icon-orange"></i>
      Your Dropbox is currently empty.
    </div>
    ';
    // echo __('Please, use the menu on the right and click on <b>"+ Add Collectibles"</b> and upload all pictures of your collectibles.');
  }
  else
  {
    echo '
    <div class="alert spacer-top-40">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <i class="icon-exclamation-sign alert-icon-orange"></i>
      Your collection is currently empty.
    </div>
    ';
    // echo __('Please, use the menu on the right and click on <b>"+ Add Collectibles"</b> and upload all pictures of your collectibles.');
  }
}
else
{
  if ($collection instanceof CollectionDropbox)
  {
    echo __('We are sorry but the Dropbox of %DisplayName% is currently empty.', array('%DisplayName%' => $collector->getDisplayName()));
    echo '&nbsp;';
    //echo __('If you are interested, you can use <b>"Message Collector"</b> to the right and encourage the collector to upload pictures of their collectibles.');
  }
  else
  {
    echo '
    <div class="alert spacer-top-40">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <i class="icon-exclamation-sign alert-icon-orange"></i>
      We are sorry but this collection is currently empty.
    </div>
    ';
    //echo __('If you are interested, you can use <b>"Message Collector"</b> to the right and encourage the collector to upload pictures of their collectibles.');
  }
}
?>

<?php if (!empty($collections)): ?>
<?php cq_section_title(__('Here are some more interesting collections:')); ?>
  <div class="row spacer-top-20">
    <div id="collectibles" class="row-content">
      <?php
        foreach ($collections as $i => $collection)
        {
          // Show the collection (in grid, list or hybrid view)
          include_partial(
            'collection/collection_grid_view_square_small',
            array('collection' => $collection, 'i' => $i)
          );
        }
        ?>
    </div>
  </div>
<?php endif; ?>
