<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(9, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetTables(array(
  'collection', 'collection_archive',
  'collector_collection', 'collection_collectible'
));

$collector = CollectorQuery::create()->findOne();

$t->diag('::setName()');

  $collection = new CollectorCollection();
  $collection->setCollector($collector);
  $collection->setName('<b>Bad Code</b> Do not allow <h2>Also</h3>');
  $collection->save();

  $t->is($collection->getName(), 'Bad Code Do not allow Also');

$t->diag('::getSlug()');

  $t->is($collection->getSlug(), 'bad-code-do-not-allow-also');

$t->diag('::getCollectibles(), ::getCollectibleIds()');

  /** @var $q CollectorCollectionQuery */
  $q = CollectorCollectionQuery::create()
     ->joinWith('Collection')
     ->useQuery('Collection')
       ->joinWith('CollectionCollectible', Criteria::RIGHT_JOIN)
     ->endUse()
     ->addDescendingOrderByColumn('RAND()');

  $collection = $q->findOne();
  $collectibles = $collection->getCollectibles();
  $collectible_ids = $collection->getCollectibleIds();

  $t->isa_ok($collectibles, 'PropelObjectCollection');
  $t->is(is_array($collectible_ids) && count($collectible_ids) > 0, true);
  $t->is(count($collectible_ids), $collectibles->count());

$t->diag('::getRandomCollectibles()');

  /** @var $q CollectorCollectionQuery */
  $q = CollectorCollectionQuery::create()
     ->joinWith('Collection')
     ->useQuery('Collection')
       ->joinWith('CollectionCollectible', Criteria::RIGHT_JOIN)
     ->endUse()
     ->addDescendingOrderByColumn('RAND()');

  $collection = $q->findOne();

  /** @var $collectible_ids1 PropelObjectCollection */
  $collectible_ids1 = $collection->getRandomCollectibles(3);

  /** @var $collectible_ids2 PropelObjectCollection */
  $collectible_ids2 = $collection->getRandomCollectibles(3);

  $t->is($collectible_ids1->count(), 3);
  $t->is($collectible_ids2->count(), 3);
  $t->is($collectible_ids1->count(), $collectible_ids2->count());
  $t->isnt($collectible_ids1->toKeyValue(), $collectible_ids2->toKeyValue());
