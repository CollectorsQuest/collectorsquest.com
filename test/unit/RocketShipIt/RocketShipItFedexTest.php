<?php
include_once(dirname(__FILE__).'/../../bootstrap/frontend.php');
include_once(dirname(__FILE__).'/../../../lib/vendor/rocketshipit/Autoloader.php');
RocketShipItAutoloader::register();

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

//sfConfig::add(array(
//    'app_rocketshipit_generic' => array(
//        'debugMode' => 1,
//        'weightUnit' => 'LB',
//    ),
//    'app_rocketshipit_fedex' => array(
//        'key' => 'HbEAp3XqDdZjfKA9',
//        'password' => '6aaiFNbjzNdjqdCnAZeeCZiig',
//        'accountNumber' => '510087941',
//        'meterNumber' => '118556825',
//    ),
//));

$t->info('Fedex Test');

$rate = new RocketShipRate('Fedex');

$rate->setParameter('shipCountry', 'US');
$rate->setParameter('shipCode', 10018);
$rate->setParameter('toCountry', 'US');
$rate->setParameter('toCode', '90210');
$rate->setParameter('weight', '5');

$response = $rate->getSimpleRates();

$t->ok(isset($response['FIRST_OVERNIGHT']),
  'First overnight');

$t->ok(isset($response['PRIORITY_OVERNIGHT']),
  'Priority overnight');

$t->ok(isset($response['STANDARD_OVERNIGHT']),
  'Standard overnight');

$t->ok(isset($response['FEDEX_2_DAY']),
  'Fedex 2 day');

$t->ok(isset($response['FEDEX_EXPRESS_SAVER']),
  'Fedex express saver');

$t->ok(isset($response['FEDEX_GROUND']),
  'Fedex ground');

$t->info('UPS Test');

$rate = new RocketShipRate('UPS');

$rate->setParameter('shipCountry', 'US');
$rate->setParameter('shipCode', 10018);
$rate->setParameter('toCountry', 'US');
$rate->setParameter('toCode', '90210');
$rate->setParameter('weight', '5');

$response = $rate->getSimpleRates();

$t->ok(isset($response['UPS Ground']),
  'UPS Ground');

$t->ok(isset($response['UPS 3 Day Select']),
  'UPS 3 Day Select');

$t->ok(isset($response['UPS 2nd Day Air']),
  'UPS 2nd Day Air');

$t->ok(isset($response['UPS Next Day Air Saver']),
  'UPS Next Day Air Saver');

$t->ok(isset($response['UPS Next Day Air Early AM']),
  'UPS Next Day Air Early AM');

$t->ok(isset($response['UPS Next Day Air']),
  'UPS Next Day Air');

//$t->info('USPS');
//$rate = new RocketShipRate('USPS');
//
//$rate->core->setTestingMode(1);
//
//$rate->setParameter('shipCountry', 'US');
//$rate->setParameter('shipCode', 10018);
//$rate->setParameter('toCountry', 'US');
//$rate->setParameter('toCode', '90210');
//$rate->setParameter('weight', '5');
//
//$response = $rate->getSimpleRates();
//var_dump($response); exit;
//$t->ok(isset($response['FIRST_OVERNIGHT']),
//  'All expected simple rates are returned');
//
//$t->ok(isset($response['PRIORITY_OVERNIGHT']),
//  'All expected simple rates are returned');
//
//$t->ok(isset($response['STANDARD_OVERNIGHT']),
//  'All expected simple rates are returned');
//
//$t->ok(isset($response['FEDEX_2_DAY']),
//  'All expected simple rates are returned');
//
//$t->ok(isset($response['FEDEX_EXPRESS_SAVER']),
//  'All expected simple rates are returned');
//
//$t->ok(isset($response['FEDEX_GROUND']),
//  'All expected simple rates are returned');
