<?php

include __DIR__ . '/../../bootstrap/unit.php';

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing cqStatic');

$t->isa_ok(cqStatic::getPayPalClient(), 'PayPal');


$t->diag('cqStatic::getGeoIpCountryCode()');

$t->is_deeply(cqStatic::getGeoIpCountryCode('127.0.0.1'), false,
  '::getGeoIpCountryCode() returns the expected result');

if (function_exists('geoip_country_code_by_name'))
{
  $t->is(cqStatic::getGeoIpCountryCode('www.government.bg'), 'BG',
    '::getGeoIpCountryCode() returns the expected result');

  $t->is(cqStatic::getGeoIpCountryCode('www.example.org'), 'US',
    '::getGeoIpCountryCode() returns the expected result');
}
