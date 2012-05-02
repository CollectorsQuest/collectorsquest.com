<?php
  slot('sidebar_300');
    include_component('search', 'sidebar', array('t' => 'wp_post'));
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
<h1 class="Chivo webfont">Blog Articles <small><?= $title; ?></small></h1>

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
    'global', 'pagination', array('pager' => $pager)
  );
?>
</div>
