<?php
  slot('sidebar_300');
    include_component('search', 'sidebar', array('t' => 'collector'));
  end_slot();
?>

<?php
  include_partial(
    'search/display_toggle',
    array('url' => $url, 'display' => $display)
  );
?>

<?php
  $title = sprintf(
    'for <strong>%s</strong> (%s)',
    $sf_params->get('q'),
    format_number_choice(
      '[0] no results|[1] 1 result|(1,+Inf] %1% results',
      array('%1%' => $pager->getNbResults()), $pager->getNbResults()
    )
  );
?>
<h1>Collectors <small><?= $title; ?></small></h1>

<div class="row">
  <div id="search-results" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $collector)
    {
      echo '<div class="span4 brick" style="height: 165px; float: left;">';
      include_partial(
        'collector/collector_'. $display .'_view',
        array('collector' => $collector, 'i' => $i)
      );
      echo '</div>';
    }
  ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
<?php
  include_component(
    'global', 'pagination',
    array('pager' => $pager, 'options' => array('id' => 'search-pagination'))
  );
?>
</div>
