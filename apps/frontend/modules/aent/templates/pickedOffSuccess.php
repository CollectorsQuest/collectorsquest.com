<?php
/**
 * @var $pager PropelModelPager
 */
?>

<div class="spacer-bottom-15">
  <?php
    echo cq_image_tag(
      'headlines/2012-0777_Picked_Off_620x180.jpg',
      array('alt_title' => 'Check out items seen on Picked Off')
    );
  ?>
</div>

<p class="text-justify">
  <?= $collection->getDescription('html'); ?>
</p>

<?php
  cq_page_title(
    'Collectibles Seen on <strong><i>Picked Off</i></strong>', null,
    array('class' => 'row-fluid header-bar spacer-bottom-15')
  );
?>

<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $collectibles Collectible[] */
    foreach ($pager->getResults() as $i => $collectible)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_grid_view_square',
        array('collectible' => $collectible, 'i' => (int) $i)
      );
    }
  ?>
  </div>
</div>

<div class="row-fluid text-center">
  <?php include_component('global', 'pagination', array('pager' => $pager)); ?>
</div>

<?php if (count($collectibles_for_sale) > 0): ?>
  <?php
    $link = link_to('See all items for sale  &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
    cq_section_title('Featured Items For Sale', $link);
  ?>

  <div id="items-for-sale">
    <div class="row thumbnails">
    <?php
      /** @var $collectibles_for_sale CollectibleForSale[] */
      foreach ($collectibles_for_sale as $i => $collectible_for_sale)
      {
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square_small',
          array('collectible_for_sale' => $collectible_for_sale, 'i' => $i)
        );
      }
    ?>
    </div>
  </div>
<?php endif; ?>
