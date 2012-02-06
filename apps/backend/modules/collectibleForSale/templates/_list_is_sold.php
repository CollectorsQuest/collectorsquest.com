<?php
/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-20 00:37:36 +0300 (Mon, 20 Jun 2011) $
 * @version $Id: _list_is_sold.php 2177 2011-06-19 21:37:36Z yanko $
 */
/* @var $CollectibleForSale CollectibleForSale */
$offer = $CollectibleForSale->getSoldOffer();

if ($offer) {
  echo '<pre>';
var_dump($offer);
echo '</pre>';

  echo $offer->getUpdatedAt('m/d/y H:M');
}