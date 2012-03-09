<?php

require 'lib/model/marketplace/om/BaseCollectibleOffer.php';

class CollectibleOffer extends BaseCollectibleOffer
{
  public function getCollectibleForSale(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollectibleForSale($con);
  }
}
