<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(2, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetTables(array(
  'collector', 'collector_profile', 'collector_email',
  'collection', 'collection_collectible',
  'collectible', 'collectible_for_sale'
));

cqTest::loadFixtures(array(
  '01_test_collectors/',
  '03_test_collectibles/'
));

$t->diag('::getCollector()');

  /** @var $collectible_for_sale CollectibleForSale */
  $collectible_for_sale = cqTest::getModelObject('CollectibleForSale', true);
  $t->isa_ok($collectible_for_sale->getCollector(), 'Collector');

$t->diag('::getCollection()');

  /** @var $collectible_for_sale CollectibleForSale */
  $collectible_for_sale = cqTest::getModelObject('CollectibleForSale', true);
  $t->is($collectible_for_sale->getCollection() instanceof Collection, true);
