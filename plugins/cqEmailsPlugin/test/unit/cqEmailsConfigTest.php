<?php

$app = 'frontend';
require_once dirname(__FILE__) . '/../bootstrap/unit.php';

$t = new lime_test(3);
$t->diag('Testing cqEmailsPlugin/lib/config/cqEmailsConfig.class.php');

// this stuff is loaded from fixtures/project/apps/frontend/config/app.yml
$t->is_deeply(cqEmailsConfig::getDataForName('Collector/successful_registration'), array(
    'subject' => 'Welcome to collectorsquest!',
    'template_path' => 'collector/successful_registration.html.twig',
    'required_params' => array(
        'collector'
    ),
    'some_default_field' => 'some default val',
));

$t->is_deeply(cqEmailsConfig::getDataForName('defaults'), array(
    'some_default_field' => 'some default val',
));

$t->is(cqEmailsConfig::getOptionForName('Collector/successful_registration', 'subject'), 'Welcome to collectorsquest!');