<?php
/**
 * @var cqFrontendUser      $sf_user
 * @var Collection  $collection
 */
?>

<?= cq_page_title($collection, 'by '. link_to_collector($collection->getCollector(), 'text')); ?>

<div class="blue-actions-panel spacer-20">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          By <?= link_to_collector($collection->getCollector(), 'text'); ?>
          </li>
        <li>
          <?php
          echo format_number_choice(
            '[0] no collectibles yet|[1] 1 Collectible|(1,+Inf] %1% Collectibles',
            array('%1%' => number_format($collection->getNumItems())), $collection->getNumItems()
          );
          ?>
        </li>
        <li>
          <?php
          echo format_number_choice(
            '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
            array('%1%' => number_format($collection->getNumViews())), $collection->getNumViews()
          );
          ?>
        </li>
      </ul>
    </div>
    <div class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <a class="btn btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= image_tag_collectible($collectible, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <!-- AddThis Button END -->
    </div>
  </div>
</div>

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
