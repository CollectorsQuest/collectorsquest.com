<?php
/*
 * @var $pager PropelModelPager
 * @var $collector Collector
 */
?>
<?php echo cq_page_title('Collectors'); ?>

<?php foreach ($pager->getResults() as $collector): ?>
<?php include_partial('collector/collector_grid_view_span6', array('collector'=> $collector)) ?>
<?php endforeach; ?>
