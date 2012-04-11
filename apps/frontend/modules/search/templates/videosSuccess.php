<?php
  slot('sidebar_300');
    include_component('search', 'sidebar', array('t' => 'video'));
  end_slot();
?>

<div id="search-display" class="btn-group"  data-toggle="buttons-radio" style="float: right; margin-top: 20px;">
  <button class="btn"><i class="icon-th"></i></button>
  <button class="btn"><i class="icon-th-list"></i></button>
</div>

<?php
  $title = sprintf(
    'for <strong>%s</strong> (%s)',
    $sf_params->get('q'),
    format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults())
  );
?>
<h1>Videos <small><?= $title; ?></small></h1>

<div class="row">
  <div id="search-results" class="row-content">
    <?php
    foreach ($pager->getResults() as $i => $video)
    {
      echo '<div class="span4 brick" style="height: 165px; float: left;">';
      include_partial(
        'video/video_'. $display .'_view',
        array('video' => $video, 'i' => $i)
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
