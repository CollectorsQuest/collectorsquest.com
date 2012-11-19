<?php
/** @var $ShoppingOrder ShoppingOrder */

$collector = $ShoppingOrder->getCollectorRelatedBySellerId();
if ($collector)
{
  echo $collector, "<br />\n";
  echo mail_to($collector->getEmail(), $collector->getEmail());
}
