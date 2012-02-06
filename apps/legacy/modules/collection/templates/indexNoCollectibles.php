<?php
/**
 * @var cqUser      $sf_user
 * @var Collection  $collection
 */
?>

<div class="clear" style="height: 20px;">&nbsp;</div>
<div class="span-17 append-bottom last">
  <div style="float: left; font-size: 32px; margin-left: 20px; margin-top: -7px; margin-right: 10px; color: #DF912F;">(!)</div>
  <?php
    if ($sf_user->isOwnerOf($collection))
    {
      echo __('Your collection is currently empty.<br>');
      echo __('Please, use the menu on the right and click on <b>"+ Add Collectibles"</b> and upload all pictures.');
    }
    else
    {
      echo __('We are sorry but this collection is currently empty.');
      echo __('If you are interested, you can use <b>"Message Collector"</b> to the right and encourage the collector to upload pictures.');
    }
  ?>
</div>

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
      array(
        'collection' => $collection,
        'culture' => $sf_user->getCulture(), 'i' => $i
      )
    );

    echo (($i + 1) % 3 == 0) ? '<br clear="all">' : null;
  }
?>
<?php endif; ?>

<?php if (!$sf_user->isAuthenticated()): ?>
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
<?php endif; ?>
