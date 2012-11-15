<?php
  if (( $header_image = $collector->getMultimediaByRole(CollectorPeer::MULTIMEDIA_ROLE_STOREFRONT_HEADER_IMAGE) ))
  {
    echo image_tag_multimedia($header_image, '620x67', array('class' => 'spacer-bottom-15'));
  }
?>

<?php
  use_helper('iceMultimedia');
  $link = link_to('Back to Seller Profile >>', 'collector_by_slug', $collector);
  cq_page_title($title, $link, array());
?>

<p class="spacer-top-15">
  <?= $collector->getSeller()->getSellerSettingsStoreDescription(); ?>
</p>

<div class="row spacer-top-20" style="margin-left: -13px;">
  <div id="collectibles" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $collectible)
    {
      if ($collectible->isForSale())
      {
        // Show the Collectible for Sale
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square',
          array(
            'collectible_for_sale' => $collectible->getCollectibleForSale(),
            'i' => (integer) $i
          )
        );
      }
      else
      {
        // Show the Collectible
        include_partial(
          'collection/collectible_grid_view_square',
          array(
            'collectible' => $collectible,
            'i' => (integer) $i
          )
        );
      }
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
