<?php
/**
 * @var $content_category  ContentCategory
 * @var $pager             sfPropelPager
 * @var $collectible_rows  integer
 * @var $sf_user           cqFrontendUser
 */

$height_main_div = new stdClass;
$height_main_div->value = 51;
?>

<?php
  cq_page_title(
    $content_category->getName(),
    link_to('Back to Market &raquo;', '@marketplace')
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

<?php $height_main_div->value += $collectible_rows * 238; ?>

<div class="row-fluid text-center">
<?php
  include_component(
    'global', 'pagination',
    array('pager' => $pager, 'height' => &$height_main_div,
          'options' => array('id' => 'collectibles-for-sale-pagination')
    )
  );
?>
</div>

<?php
  $height_main_div->value > 500 ?: $height_main_div->value = 500;
  $sf_user->setFlash('height_main_div', $height_main_div, false, 'internal');
?>
