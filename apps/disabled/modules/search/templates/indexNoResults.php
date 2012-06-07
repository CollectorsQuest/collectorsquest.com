<br clear="all">
<div class="span-15 append-bottom last">
  <div style="float: left; font-size: 32px; margin-left: 20px; margin-top: -7px; margin-right: 10px; color: #DF912F;">(!)</div>
  <?= __('We are sorry but your search did not return any matches.'); ?>
  <?= __('You can change your search criteria or use the "Advanced Search" to the right.'); ?>
</div>

<br clear="all"><br>
<?php cq_section_title(__('Here are some of the most popular collections:')); ?>
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

<?php if (!$sf_user->isAuthenticated()): ?>
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
<?php endif; ?>
