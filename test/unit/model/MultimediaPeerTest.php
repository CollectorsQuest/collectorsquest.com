<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(13, new lime_output_color());

$images = array(
  sfConfig::get('sf_test_dir').'/data/multimedia/05620d783231c09402ea1d406d35a58c.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/787d7bfb4d440de2ef136f097683f426.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/c71794c2b0bf9fd8a9cd31abda2ed70b.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/4fbe6dbd481c49c4eab4a2f12e7808f9.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/38eb5d37b931b5c7c6429bc07600fbb9.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/collectible-1911.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/collectible-31.jpg',
  sfConfig::get('sf_test_dir').'/data/multimedia/collectible-1357.jpg'
);

$t->diag('::getValidContentTypes()');

  $t->is(is_array(MultimediaPeer::getValidContentTypes()), true);

$t->diag('::has()');

  $collector = CollectorPeer::retrieveByPK(3);
  $collector->setPhoto($images[0]);

  $t->is(MultimediaPeer::has($collector, 'image'), true);
  $t->is(MultimediaPeer::has($collector, 'image', true), true);
  $t->is(MultimediaPeer::has($collector, 'image', false), false);

  $collectible = CollectiblePeer::retrieveByPK(16222);

  $collectible->addMultimedia($images[0], true);
  $collectible->addMultimedia($images[1], false);
  $t->is(MultimediaPeer::has($collectible, 'image'), true);
  $t->is(MultimediaPeer::has($collectible, 'image', false), true);

$t->diag('::makeThumb()');

  $thumb = MultimediaPeer::makeThumb($images[2], '75x75', 'shave');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(75, 75));

  $thumb = MultimediaPeer::makeThumb($images[2], '170x230', 'shave');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(170, 230));

  $thumb = MultimediaPeer::makeThumb($images[3], '170x230', 'shave');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(170, 230));

  $thumb = MultimediaPeer::makeThumb($images[2], '420x1000', 'bestfit');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(420, 560));

  $thumb = MultimediaPeer::makeThumb($images[5], '1024x768', 'bestfit');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(577, 768));

  $thumb = MultimediaPeer::makeThumb($images[6], '230x150', 'shave');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(230, 150));

  $thumb = MultimediaPeer::makeThumb($images[7], '150x150', 'shave');
  list($width, $height) = @getimagesize($thumb);
  $t->is(array($width, $height), array(150, 150));
