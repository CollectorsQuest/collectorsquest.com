<?php
include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
include_once(dirname(__FILE__).'/../../../lib/vendor/rocketshipit/Autoloader.php');
RocketShipItAutoloader::register();

$t = new lime_test(null, new lime_output_color());

sfConfig::add(array(
    'app_rocketshipit_generic' => array(
        'debugMode' => 1,
        'weightUnit' => 'LB',
    ),
    'app_rocketshipit_fedex' => array(
        'key' => 'HbEAp3XqDdZjfKA9',
        'password' => '6aaiFNbjzNdjqdCnAZeeCZiig',
        'accountNumber' => '510087941',
        'meterNumber' => '118556825',
    ),
));

$rate = new RocketShipRate('Fedex');

$rate->setParameter('shipCountry', 'US');
$rate->setParameter('shipCode', 10018);
$rate->setParameter('toCountry', 'US');
$rate->setParameter('toCode','90210');
$rate->setParameter('weight','5');

$response = $rate->getSimpleRates();

$t->is(count($response), 6, 'Fedex returns 6 separate simple rates');