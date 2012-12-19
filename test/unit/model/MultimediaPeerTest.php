<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(0, array('output' => new lime_output_color(), 'error_reporting' => true));

// Reset all tables we will be working on
cqTest::resetTables(array('iceModelMultimedia', 'collectible'));

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
