<?php
/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-20 01:00:07 +0300 (Mon, 20 Jun 2011) $
 * @version $Id: _collectible.php 2179 2011-06-19 22:00:07Z yanko $
 */
/* @var $CollectibleForSale CollectibleForSale */
echo link_to($CollectibleForSale->getCollectible(), 'item_offers', array('id'=>$CollectibleForSale->getId(), 'action'=>'items'));