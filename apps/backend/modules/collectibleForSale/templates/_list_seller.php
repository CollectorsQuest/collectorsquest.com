<?php
/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-20 01:08:26 +0300 (Mon, 20 Jun 2011) $
 * @version $Id: _seller.php 2180 2011-06-19 22:08:26Z yanko $
 */
/* @var $CollectibleForSale CollectibleForSale */
$collectible = $CollectibleForSale->getCollectible();
$collector = $collectible->getCollector();

echo $collector, "<br />\n";
echo mail_to($collector->getEmail(), $collector->getEmail());

