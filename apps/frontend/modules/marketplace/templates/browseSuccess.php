<?php
/**
 * @var $content_category ContentCategory
 * @var $pager sfPropelPager
 */
?>

<?php
  cq_page_title(
    $content_category->getName()
  );
?>

<br/>
<div class="row" style="margin-left: -13px;">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $collectible_for_sale CollectibleForSale */
    foreach ($pager->getResults() as $i => $collectible_for_sale)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'marketplace/collectible_for_sale_grid_view_square',
        array('collectible_for_sale' => $collectible_for_sale, 'i' => (int) $i)
      );
    }
  ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
<?php
  include_component(
    'global', 'pagination',
    array('pager' => $pager, 'options' => array('id' => 'collectibles-for-sale-pagination'))
  );
?>
</div>
