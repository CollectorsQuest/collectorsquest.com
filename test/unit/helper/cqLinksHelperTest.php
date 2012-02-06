<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
$configuration->loadHelpers('cqLinks');

new sfDatabaseManager($configuration);

$t = new lime_test(0, new lime_output_color());

$t->diag('::link_to_collection()');


$t->diag('::link_to_collector()');


$t->diag('::link_to_collectible()');
