<?php
  /**
   * @var $collectibles_for_sale CollectibleForSale[]
   */

  foreach ($collectibles_for_sale as $collectible_for_sale)
  {
    $collectible = $collectible_for_sale->getCollectible();
    $seller = $collectible_for_sale->getCollector();

    echo implode('&nbsp|&nbsp', array(
      $seller->getDisplayName(),
      image_tag_collectible($collectible),
      $collectible->getName(),
      $collectible_for_sale->getPrice()
    ));

    echo '<br/><br/>';
  }
?>
