<?php
/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-23 22:58:26 +0300 (Thu, 23 Jun 2011) $
 * @version $Id: _offers_count.php 2189 2011-06-23 19:58:26Z yanko $
 */
/* @var $CollectibleForSale CollectibleForSale */
echo link_to_if(
  $CollectibleForSale->getActiveCollectibleOffersCount(), 
  $CollectibleForSale->getActiveCollectibleOffersCount(), 
  'item_offers', 
  array('id'=>$CollectibleForSale->getId())
    );