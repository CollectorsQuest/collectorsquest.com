<?php
/** @var $PromotionTransaction PromotionTransaction */

$collector = $PromotionTransaction->getCollector();

echo $collector, "<br />\n";
echo mail_to($collector->getEmail(), $collector->getEmail());
