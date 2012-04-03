<?php
  slot('sidebar_300');
    include_component('search', 'sidebar', array('t' => 'collectible'));
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
    format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => 23), 23)
  );
  cq_page_title('Collectibles', $title);
?>
