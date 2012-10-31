<?php
include_once(dirname(__FILE__).'/../../bootstrap/model.php');
require_once(dirname(__FILE__).'/../../../lib/widget/cqWidgetFormChoiceGeoIpCountry.class.php');

$t = new lime_test(7, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/widget/cqWidgetFormChoiceGeoIpCountry.class.php');

$w = new cqWidgetFormChoiceGeoIpCountry(array(
    'add_empty' => false,
    'keep_common_in_full_list' => true,
    'common_separator' => '-',
    'countries' => array('US', 'FR', 'BG', 'RO'),
));

$w->buildCountriesList();
$t->cmp_ok($w->getChoices(), '===', array(
    'FR' => 'France',
    'US' => 'United States',
    ''   => '-',
    'BG' => 'Bulgaria',
    'RO' => 'Romania'
), 'Keep common countries in list');

$w->setOption('keep_common_in_full_list', false);
$w->buildCountriesList();
$t->cmp_ok($w->getChoices(), '===', array(
    'FR' => 'France',
    'US' => 'United States',
    ''   => '-',
    'BG' => 'Bulgaria',
    'RO' => 'Romania',
), 'Don\'t keep common countries in list');

$w->setOption('common_separator', false);
$w->buildCountriesList();
$t->cmp_ok($w->getChoices(), '===', array(
    'FR' => 'France',
    'US' => 'United States',
    'BG' => 'Bulgaria',
    'RO' => 'Romania',
), 'Remove separator between common and all countries');

$w->setOption('add_worldwide', true);
$w->buildCountriesList();
$t->cmp_ok($w->getChoices(), '===', array(
    'FR' => 'France',
    'US' => 'United States',
    'ZZ' => 'Worldwide',
    'BG' => 'Bulgaria',
    'RO' => 'Romania',
), 'Add worldwide');
$w->setOption('add_worldwide', false);


if (function_exists('geoip_country_code_by_name'))
{
  $w->setOption('remote_address', 'www.example.org');
  $w->buildCountriesList();
  $t->cmp_ok($w->getChoices(), '===', array(
      'US' => 'United States',
      'FR' => 'France',
      'BG' => 'Bulgaria',
      'RO' => 'Romania',
  ), 'Reorder the list based on geoip (common countries)');

  $w->setOption('remote_address', 'www.diplomatie.gouv.fr');
  $w->buildCountriesList();
  $t->cmp_ok($w->getChoices(), '===', array(
      'FR' => 'France',
      'US' => 'United States',
      'BG' => 'Bulgaria',
      'RO' => 'Romania',
  ), 'Reorder the list based on geoip (common countries)');

  $w->setOption('remote_address', 'www.government.bg');
  $w->buildCountriesList();
  $t->cmp_ok($w->getChoices(), '===', array(
      'BG' => 'Bulgaria',
      'FR' => 'France',
      'US' => 'United States',
      'RO' => 'Romania',
  ), 'Reorder the list based on geoip (all countries)');
}
