<?php
/* @var $sf_user                   cqFrontendUser */
/* @var $collector                 Collector */
/* @var $collectibles_for_sale     CollectibleForSale[] */
/* @var $title                     string */
/* @var $height                    stdClass */
/* @var $display_store_link        boolean */
?>

<?php
  if ($display_store_link)
  {
    $link = link_to('Visit the store &raquo;', 'seller_store', $collector, array('class' => 'text-v-middle link-align'));
  }
  else
  {
    $link = link_to('Visit our marketplace &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
  }

  cq_section_title($title, $link, $options = array('left'=> 9, 'right' => 3, 'style' => 'margin-top: 0px;'));
?>

<div id="user-collectibles-for-sale">
  <div class="row thumbnails">
    <?php
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

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value += 225;
  }
?>

