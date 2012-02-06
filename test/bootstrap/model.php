<?php

include __DIR__ .'/unit.php';

$application = $configuration->getApplicationConfiguration('frontend', 'test', isset($debug) ? $debug : true);
$context = sfContext::createInstance($application);
