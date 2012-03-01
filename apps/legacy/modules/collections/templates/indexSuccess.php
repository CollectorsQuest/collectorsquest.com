<br clear="all" /><br />

<?php
/* @var $pager sfPropelPager */
/* @var $sf_user cqUser */
$offset = 0;
$collections = $pager->getResults();
?>
<div id="collections">
  <?php foreach ($collections as $i => $collection): ?>
  <?php
  // Show the collection (in grid, list or hybrid view)
  include_partial(
    'collections/' . $display . '_view_collection',
    array(
      'collection' => $collection,
      'culture'    => $sf_user->getCulture(),
      'i'          => $i
    )
  ); ?>

  <?php if (0 == ($i + $offset + 1) % 3): ?>
    <br clear="all" />
    <?php endif; ?>
  <?php endforeach; ?>

  <br clear="all" />

  <div class="span-19 last" style="margin-bottom: 25px">
    <?php include_partial('global/pager', array(
    'pager'  => $pager,
    'options'=> array('url'   => '@collections_by_filter?filter=' . $filter)
  )); ?>
  </div>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
<?php endif; ?>

