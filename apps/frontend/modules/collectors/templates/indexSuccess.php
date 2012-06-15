<?php
/**
 * @var $pager PropelModelPager
 * @var $collector Collector
 * @var $type string
 * @var $display string
 */
?>

<?php cq_page_title('sellers' == $type ? 'Sellers' : 'Collectors', '&nbsp;'); ?>

<br/>
<div class="row">
  <div id="collectors" class="row-content">
    <?php foreach ($pager->getResults() as $i => $collector): ?>
    <div class="span6" style="height: 100px; float: left;">
      <?php
        include_partial(
          'collector/collector_' . $display . '_view_compact',
          array('collector' => $collector, 'i' => $i)
        );
      ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
<?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
?>
</div>
