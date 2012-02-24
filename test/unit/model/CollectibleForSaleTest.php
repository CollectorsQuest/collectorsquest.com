<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(2, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetTables(array('collectible_for_sale'));

$t->diag('::getCollector()');

  $collectible_for_sale = cqTest::getModelObject('CollectibleForSale', true);
  $t->isa_ok($collectible_for_sale->getCollector(), 'Collector');

$t->diag('::getCollection()');

  $collectible_for_sale = cqTest::getModelObject('CollectibleForSale', true);
  $t->isa_ok($collectible_for_sale->getCollection(), 'Collection');

$t->diag('::getOffersCount()');



$t->diag('::getCollectibleOfferByBuyer()');


$t->diag('::getActiveCollectibleOffersCount()');


$t->diag('::getSoldOffer()');


$t->diag('::getBackendIsSold()');
