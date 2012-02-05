<?php
/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-20 01:08:26 +0300 (Mon, 20 Jun 2011) $
 * @version $Id: _buyer.php 2180 2011-06-19 22:08:26Z yanko $
 */
/* @var $CollectibleForSale CollectibleForSale */
$offer = $CollectibleForSale->getSoldOffer();

if ($offer)
{
  $collector = $offer->getCollector();
  echo $collector, "<br />\n";
  ?>
  <a href="mailto:<?php echo $collector->getEmail() ?>"><?php echo $collector->getEmail() ?></a>
  <?php
}