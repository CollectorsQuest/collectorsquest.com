<?php

require_once dirname(__FILE__) .'/../../plugins/iceLibsPlugin/lib/test/IceWebTestResponse.class.php';
require_once dirname(__FILE__) .'/../../plugins/iceLibsPlugin/lib/test/IceWebTestRequest.class.php';

$configuration = $configuration->getApplicationConfiguration('legacy', 'test', true);
$context = sfContext::createInstance($configuration, 'default');
