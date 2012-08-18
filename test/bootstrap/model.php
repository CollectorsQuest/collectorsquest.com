<?php

include __DIR__ .'/unit.php';

/** @var $sf_configuration sfProjectConfiguration */
$sf_application = $sf_configuration->getApplicationConfiguration(
  isset($app) ? $app : 'frontend', 'test', isset($debug) ? $debug : true
);

$sf_context = sfContext::createInstance($sf_application, null, 'cqTestContext');
