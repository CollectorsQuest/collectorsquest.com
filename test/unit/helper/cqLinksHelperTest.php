<?php

include(__DIR__.'/../../bootstrap/unit.php');

$sf_configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
$sf_configuration->loadHelpers('cqLinks');

new sfDatabaseManager($sf_configuration);

$t = new lime_test(0, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('::link_to_collection()');


$t->diag('::link_to_collector()');


$t->diag('::link_to_collectible()');
