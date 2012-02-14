<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(13, array(new lime_output_color()));

cqTest::resetTables(array('collection', 'collection_archive', 'collector_collection'));

$collector = CollectorQuery::create()->findOne();

$t->diag('::setName()');

  $collection = new CollectorCollection();
  $collection->setCollector($collector);
  $collection->setName('<b>Bad Code</b> Do not allow <h2>Also</h3>');
  $collection->save();

  $t->is($collection->getName(), 'Bad Code Do not allow Also');

$t->diag('::getSlug()');

  $t->is($collection->getSlug(), 'bad-code-do-not-allow-also');

$t->diag('::setDescription()');

  $collection = new CollectorCollection();
  $collection->setDescription('<p>An <a href="http://example.com/" title="Title">example</a>. Then, anywhere else in the doc, define the link:</p>', 'html');
  $t->is($collection->getDescription('markdown'), 'An example. Then, anywhere else in the doc, define the link:');
  $t->is($collection->getDescription('html'), "<p>An example. Then, anywhere else in the doc, define the link:</p>\n");

  $collection = new CollectorCollection();
  $collection->setDescription('<h1>Header 1</h1><h2>Header 2</h2><h6>Header 6</h6>', 'html');
  $t->is(str_replace("\n", ' ', $collection->getDescription('markdown')), '# Header 1  ## Header 2  ###### Header 6');
  $t->is($collection->getDescription('html'), "<h1>Header 1</h1>\n\n<h2>Header 2</h2>\n\n<h6>Header 6</h6>\n");

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
