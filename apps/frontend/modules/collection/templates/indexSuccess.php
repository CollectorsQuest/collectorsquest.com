<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $display     string
 * @var  $collection  Collection
 * @var  $pager       sfPropelPager
 */
?>

<?= cq_page_title($collection, 'by '. link_to_collector($collection->getCollector(), 'text')); ?>

<div class="row">
  <?php
    /** @var $collectible Collectible */
    foreach ($pager->getResults() as $i => $collectible)
    {
      echo '<div class="', ('list' == $display ? 'span6' : 'span4'), '">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_'. $display .'_view',
        array(
          'collectible' => $collectible,
          'culture' => (string) $sf_user->getCulture(),
          'i' => (int) $i
        )
      );
      echo '</div>';
    }
  ?>
</div>
