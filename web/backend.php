<?php

require __DIR__ .'/../config/bootstrap.php';
require __DIR__ .'/../config/ProjectConfiguration.class.php';

/** @var cqApplicationConfiguration $configuration */
$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP, SF_ENV, SF_DEBUG);

// Handle the request
sfContext::createInstance($configuration, null, 'cqContext')->dispatch();
