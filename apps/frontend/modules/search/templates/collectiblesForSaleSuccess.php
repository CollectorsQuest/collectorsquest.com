<?php
/* @var $pager    cqSphinxPager */
/* @var $url      string */
/* @var $display  string */
?>

<?php
  slot('sidebar_300');
    include_component('search', 'sidebar', array('t' => 'collectible_for_sale'));
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
<h1 class="Chivo webfont">Items for Sale <small><?= $title; ?></small></h1>

<div class="row">
  <div id="search-results" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $collectible)
    {
      if (!$collectible_for_sale = $collectible->getCollectibleForSale()) {
        continue;
      }

      include_partial(
        'marketplace/collectible_for_sale_'. $display .'_view_square',
        array('collectible_for_sale' => $collectible_for_sale, 'i' => $i)
      );
    }
  ?>
  </div>
</div>

<div class="row-fluid text-center">
<?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
?>
</div>
