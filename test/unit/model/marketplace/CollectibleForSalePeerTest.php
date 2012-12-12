<?php

include(__DIR__.'/../../../bootstrap/model.php');
include(__DIR__.'/CollectibleForSaleTestHelper.php');
require_once dirname(__FILE__).'/../../../../lib/model/marketplace/CollectibleForSalePeer.php';

$t = new lime_test(26, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('Testing lib/model/marketplace/CollectibleForSalePeer.php');

cqTest::resetClasses(array('Collector', 'Collectible'));
cqTest::loadFixtures(array('01_test_collectors', '03_test_collectibles'), true);

$active = collectible_for_sale_by_slug('active');
$inactive = collectible_for_sale_by_slug('inactive');
$expired = collectible_for_sale_by_slug('expired');
$sold = collectible_for_sale_by_slug('sold');
$second_sold = collectible_for_sale_by_slug('second_sold');


$t->diag('::activate()');

  $t->ok(!$inactive->isForSale());
  $t->ok(CollectibleForSalePeer::activate($inactive));
  $t->ok($inactive->isForSale());

  $t->ok($active->isForSale());
  $t->ok(!CollectibleForSalePeer::activate($active));
  $t->ok($active->isForSale());

  $t->ok($expired->isForSale());
  $t->ok(!CollectibleForSalePeer::activate($expired));
  $t->ok($active->isForSale());

$t->diag('::deactivate()');

  $inactive->setIsReady(false)->save();

  $t->ok(!$inactive->isForSale());
  $t->ok(!CollectibleForSalePeer::deactivate($inactive));
  $t->ok(!$inactive->isForSale());

  $t->ok($active->isForSale());
  $t->ok(CollectibleForSalePeer::deactivate($active));
  $t->ok(!$active->isForSale());

$t->diag('::relist()');

  $t->isa_ok(CollectibleForSalePeer::relist($active), 'NULL');
  $t->isa_ok(CollectibleForSalePeer::relist($inactive), 'NULL');

  $t->ok(!$expired->hasActiveCredit());
  $t->isa_ok($returnedExpired = CollectibleForSalePeer::relist($expired), 'CollectibleForSale');
  $t->ok($returnedExpired->equals($expired));
  $t->ok($returnedExpired->hasActiveCredit());

  $t->ok($sold->hasActiveCredit());
  $t->isa_ok($returnedSold = CollectibleForSalePeer::relist($sold), 'CollectibleForSale');
  $t->ok(!$returnedSold->equals($sold));
  $t->ok($returnedSold->hasActiveCredit());

  // no credits left, so null returned
  $t->isa_ok(CollectibleForSalePeer::relist($second_sold), 'NULL');