<?php
include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
include_once(dirname(__FILE__).'/../../../lib/vendor/rocketshipit/Autoloader.php');
RocketShipItAutoloader::register();

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

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
$rate->setParameter('toCode', '90210');
$rate->setParameter('weight', '5');

$response = $rate->getSimpleRates();

$t->ok(isset($response['FIRST_OVERNIGHT']),
  'All expected simple rates are returned');

$t->ok(isset($response['PRIORITY_OVERNIGHT']),
  'All expected simple rates are returned');

$t->ok(isset($response['STANDARD_OVERNIGHT']),
  'All expected simple rates are returned');

$t->ok(isset($response['FEDEX_2_DAY']),
  'All expected simple rates are returned');

$t->ok(isset($response['FEDEX_EXPRESS_SAVER']),
  'All expected simple rates are returned');

$t->ok(isset($response['FEDEX_GROUND']),
  'All expected simple rates are returned');
