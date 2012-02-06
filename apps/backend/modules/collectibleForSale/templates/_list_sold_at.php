<?php
/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-20 00:37:36 +0300 (Mon, 20 Jun 2011) $
 * @version $Id: _list_sold_at.php 2177 2011-06-19 21:37:36Z yanko $
 */
/* @var $CollectibleForSale CollectibleForSale */
$offer = $CollectibleForSale->getSoldOffer();

if ($offer) {
  echo $offer->getUpdatedAt('m/d/Y H:i');
}