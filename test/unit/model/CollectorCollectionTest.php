<?php

include __DIR__ .'/../../bootstrap/model.php';

$t = new lime_test(0, array(new lime_output_color()));

cqTest::resetTables(array('collector', 'collection', 'collector_collection'));

$collector = new Collector();
$collector->setUsername('test');
$collector->setPassword('test');
$collector->setEmail('test@test.com');
$collector->save();

$collector_collection = new CollectorCollection();
$collector_collection->setCollector($collector);
$collector_collection->setName('test');
$collector_collection->save();
