<?php
  /**
   * @var $collectibles_for_sale CollectibleForSale[]
   */

  foreach ($collectibles_for_sale as $collectible_for_sale)
  {
    $collectible = $collectible_for_sale->getCollectible();

    echo $collectible->getName() .' - '. $collectible_for_sale->getPrice();
  }
?>
