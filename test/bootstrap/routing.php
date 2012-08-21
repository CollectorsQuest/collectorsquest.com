<?php

require_once __DIR__ .'/../../plugins/iceLibsPlugin/lib/test/IceWebTestResponse.class.php';
require_once __DIR__ .'/../../plugins/iceLibsPlugin/lib/test/IceWebTestRequest.class.php';

/** @var $sf_configuration sfProjectConfiguration */
$sf_application = $sf_configuration->getApplicationConfiguration(isset($app) ? $app : 'frontend', 'test', true);
$sf_context = sfContext::createInstance($sf_application, 'default', 'cqTestContext');
