<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(22, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetClasses(array('Collector'));

$t->diag('::setEmail()');

  $collector = cqTest::getModelObject('Collector', true);
  $collector->setEmail('nobody@collectorsquest.com');
  $t->is($collector->getEmail(), 'nobody@collectorsquest.com');

$t->diag('::getCollections(), ::countCollections()');

  /** @var $q CollectorQuery */
  $q = CollectorQuery::create()
     ->joinWith('CollectorCollection', Criteria::RIGHT_JOIN)
     ->addAscendingOrderByColumn('RAND()');

  $collector = $q->findOne();
  $t->isa_ok($collector->getCollections(),  'PropelObjectCollection');
  $t->is($collector->countCollections() > 0,  true);
  $t->is($collector->getCollections()->count(), $collector->countCollections());


$t->diag("::getSalt()");

  $new_collector = new Collector();
  $salt = $new_collector->getSalt();
  $t->is(strlen($salt), 32,
    "getSalt() will generate a new md5 hash on first run");
  $t->is($salt, $new_collector->getSalt(),
    'getSalt() returns the same salt after first run');


$t->diag("::checkPassword()");
  $new_collector = new Collector();
  $new_collector->setPassword('ggbg');
  $t->is($new_collector->checkPassword('not-ggbg'), false,
    'checkPassword() returns false for wrong password');
  $t->is($new_collector->checkPassword('ggbg'), true,
    'checkPassword() return ture for right password');


$t->diag("Test tagging functions");
  $new_collector = new Collector();

  $new_collector->addICollectTag('ponies');
  $t->is_deeply($new_collector->getICollectTags(), array('ponies'),
    '::add/setICollectTags()');

  $new_collector->addICollectTag('ponies');
  $t->is_deeply($new_collector->getICollectTags(), array('ponies'),
    '::add/setICollectTags()');

  $new_collector->removeAllICollectTags();
  $t->is_deeply($new_collector->getICollectTags(), array(),
    '::removeAllICollectTags()');

  $new_collector->addICollectTag('ponies, plushies');
  $t->is_deeply($new_collector->getICollectTags(), array('ponies', 'plushies'),
    '::add/setICollectTags()');

  $new_collector->addICollectTag(array('art prints', 'books'));
  $t->is_deeply($new_collector->getICollectTags(), array('ponies', 'plushies', 'art prints', 'books'),
    '::add/setICollectTags()');

  $new_collector->setICollectTags(array('luna', 'is', 'best', 'pony'));
  $t->is_deeply($new_collector->getICollectTags(), array('luna', 'is', 'best', 'pony'),
    '::setICollectTags()');


  $new_collector->addISellTag('ponies');
  $t->is_deeply($new_collector->getISellTags(), array('ponies'),
    '::add/setISellTags()');

  $new_collector->addISellTag('ponies');
  $t->is_deeply($new_collector->getISellTags(), array('ponies'),
    '::add/setISellTags()');

  $new_collector->removeAllISellTags();
  $t->is_deeply($new_collector->getISellTags(), array(),
    '::removeAllISellTags()');

  $new_collector->addISellTag('ponies, plushies');
  $t->is_deeply($new_collector->getISellTags(), array('ponies', 'plushies'),
    '::add/setISellTags()');

  $new_collector->addISellTag(array('art prints', 'books'));
  $t->is_deeply($new_collector->getISellTags(), array('ponies', 'plushies', 'art prints', 'books'),
    '::add/setISellTags()');

  $new_collector->setISellTags(array('twilight', 'sparkle', 'disagrees'));
  $t->is_deeply($new_collector->getISellTags(), array('twilight', 'sparkle', 'disagrees'),
    '::setISellTags()');


  $new_collector->addTag('standalone-tag');
  $t->is_deeply($new_collector->getTags(array('is_triple' => false)),
    array('standalone-tag' => 'standalone-tag'),
    '::getTags()');

  $t->is_deeply($new_collector->getTagsString(), 'standalone-tag, luna, is, best, pony, twilight, sparkle, disagrees',
    '::getTagsString()');
