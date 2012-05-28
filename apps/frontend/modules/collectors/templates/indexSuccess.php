<?php
/*
 * @var $pager PropelModelPager
 * @var $collector Collector
 * @var $sortBy string
 * @var $type string
 */
?>
<h1 class="Chivo webfont"><?='sellers' == $type ? 'Sellers' : 'Collectors'?></h1>

<div class="row">
  <div id="search-results" class="row-content">
    <?php foreach ($pager->getResults() as $i => $collector): ?>
    <div class="span6 brick" style="height: 165px; float: left;">
      <?php       include_partial(
      'collector/collector_' . $display . '_view_span6',
      array(
        'collector' => $collector,
        'i'         => $i
      )
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
