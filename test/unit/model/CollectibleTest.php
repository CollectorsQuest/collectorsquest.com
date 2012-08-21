<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(10, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetClasses(array('Collectible'));

$t->diag('::setName()');

  $collectible = new Collectible();
  $collectible->setName('<b>Bad Code</b> Do not allow <h2>Also</h3>');
  $t->is($collectible->getName(), 'Bad Code Do not allow Also');

$t->diag('Setting and getting the slug');

  $collectible = cqTest::getNewModelObject('Collectible');
  $collectible->setName('Untitled Item');
  $collectible->setDescription('No description required here');
  $collectible->save();

  $collectible = cqTest::getNewModelObject('Collectible');
  $collectible->setName('Untitled Item');
  $collectible->setDescription('No description required here also');
  $collectible->save();

  $t->like($collectible->getSlug(), '/untitled-item-\w+/i');

$t->diag('::getCollection(), ::getCollectionId()');

  $q = CollectibleQuery::create()
     ->joinCollectionCollectible();

  if ($collectible = $q->findOne())
  {
    $t->isa_ok($collectible->getCollection(), 'Collection');
    $t->isnt($collectible->getCollectionId(), null);
  }
  else
  {
    $t->skip('No CollectionCollectible found to test with', 2);
  }

$t->diag('::setThumbnail()');

  $collectible = cqTest::getModelObject('Collectible');
  $collectible->setThumbnail(sfConfig::get('sf_test_dir') .'/data/multimedia/movie-mark-03-armor-33130.jpg');
  $collectible->save();

  /** @var $multimedia iceModelMultimedia */
  $multimedia = $collectible->getPrimaryImage();

  $t->is($multimedia->fileExists('75x75'), true);
  $t->is($multimedia->fileExists('thumbnail'), true);
  $t->is($multimedia->fileExists('190x150'), true);
  $t->is($multimedia->fileExists('190x190'), true);
  $t->is($multimedia->fileExists('260x205'), true);
  $t->is($multimedia->fileExists('620x0'), true);
