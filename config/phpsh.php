<?php

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/ProjectConfiguration.class.php';

/* @var cqApplicationConfiguration $configuration */
$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP, SF_ENV, false);

/* @var $context cqContext */
$context = sfContext::createInstance($configuration, null, 'cqContext');
