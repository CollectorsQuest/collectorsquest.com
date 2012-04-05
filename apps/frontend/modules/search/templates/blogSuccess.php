<?php
  slot('sidebar_300');
    include_component('search', 'sidebar', array('t' => 'wp_post'));
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
  cq_page_title('New Articles', $title);
?>

<div id="search-results">
  <div class="row-fluid">
  <?php
    foreach ($pager->getResults() as $i => $wp_post)
    {
      echo '<div class="span6">';
      include_partial(
        'news/wp_post_'. $display .'_view',
        array('wp_post' => $wp_post, 'excerpt' => $pager->getExcerpt($i), 'i' => $i)
      );
      echo '</div>';

      if (($i+1) % 2 == 0) echo '</div><div class="row-fluid">';
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
