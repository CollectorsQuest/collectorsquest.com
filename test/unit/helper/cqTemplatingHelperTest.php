<?php

include(__DIR__.'/../../bootstrap/unit.php');

require_once(sfConfig::get('sf_symfony_lib_dir').'/../test/unit/sfContextMock.class.php');
require_once(__DIR__.'/../../../lib/vendor/symfony/lib/helper/DateHelper.php');
require_once(__DIR__.'/../../../lib/vendor/symfony/lib/helper/I18NHelper.php');
require_once(__DIR__.'/../../../lib/helper/cqTemplatingHelper.php');

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

class sfUser
{
  public $culture = 'en';

  public function getCulture()
  {
    return $this->culture;
  }
}
sfConfig::set('sf_charset', 'utf-8');
$context = sfContext::getInstance(array('user' => 'sfUser'));

$now = strtotime('2012-04-09 20:00:00');

$t->diag('distance_of_time_in_short_words()');

$t->is(distance_of_time_in_short_words($now - 2, $now), 'less than a min');
$t->is(distance_of_time_in_short_words($now - 60 * 1, $now), '1m');
$t->is(distance_of_time_in_short_words($now - 60 * 5, $now), '5m');
$t->is(distance_of_time_in_short_words($now - 60 * 50, $now), '1h');
$t->is(distance_of_time_in_short_words($now - 60 * 65, $now), '1h 5m');
$t->is(distance_of_time_in_short_words($now - 3600 * 5, $now), '5h');
$t->is(distance_of_time_in_short_words($now - 3600 * 5, $now, false), '5h');
$t->is(distance_of_time_in_short_words($now - 3600 * 5 - 60 * 30, $now), '5h 30m');
$t->is(distance_of_time_in_short_words($now - 3600 * 5 - 60 * 30, $now, false), '6h');
$t->is(distance_of_time_in_short_words($now - 86400 * 1, $now), '1d');
$t->is(distance_of_time_in_short_words($now - 86400 * 1  - 3600 * 5, $now), '1d 5h');
$t->is(distance_of_time_in_short_words($now - 86400 * 1  - 3600 * 5 + 60 * 30, $now), '1d 4h');
$t->is(distance_of_time_in_short_words($now - 86400 * 1  - 3600 * 5 - 60 * 30, $now), '1d 5h');

$t->is(distance_of_time_in_short_words($now - 86400 * 5, $now), '5d');
$t->is(distance_of_time_in_short_words($now - 86400 * 5  - 3600 * 5, $now), '5d 5h');
$t->is(distance_of_time_in_short_words($now - 86400 * 5  - 3600 * 5 + 60 * 30, $now), '5d 5h');
$t->is(distance_of_time_in_short_words($now - 86400 * 5  - 3600 * 5 - 60 * 30, $now), '5d 6h');
$t->is(distance_of_time_in_short_words($now - 2592000 * 1, $now), '1m');
$t->is(distance_of_time_in_short_words($now - 2592000 * 1 + 86400 * 1, $now), '29d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 1 - 86400 * 5, $now), '1m 5d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 1 - 86400 * 5 - 3600 * 12, $now), '1m 6d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 1 - 86400 * 5 + 3600 * 12, $now), '1m 5d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5, $now), '5m');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5 + 86400 * 1, $now), '4m 29d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5 - 86400 * 5, $now), '5m 5d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5 - 86400 * 20, $now), '5m 20d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5 - 86400 * 5 - 3600 * 12, $now), '5m 6d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5 - 86400 * 5 + 3600 * 12, $now), '5m 5d');
$t->is(distance_of_time_in_short_words($now - 2592000 * 5 - 86400 * 20 + 3600 * 12, $now), '5m 20d');
$t->is(distance_of_time_in_short_words($now - 31557600, $now), '1y');
$t->is(distance_of_time_in_short_words($now - 31557600 - 1295500, $now), '1y');
$t->is(distance_of_time_in_short_words($now - 31557600 - 2591000, $now), '1y 1m');
$t->is(distance_of_time_in_short_words($now - 31557600 - 2592000 * 1, $now), '1y 1m');
$t->is(distance_of_time_in_short_words($now - 31557600 - 2592000 * 5, $now), '1y 5m');

$t->diag('cq_time_ago_in_words_or_exact_date()');

$cutoff = '-7 days';
$dt_f = 'On MMM d, yyyy, h:mm:s a';
$t->is(time_ago_in_words_or_exact_date(strtotime('-1 day', $now), $cutoff, $dt_f, $now), '1d ago');
$t->is(time_ago_in_words_or_exact_date(strtotime('-1 day -5 hours', $now), $cutoff, $dt_f, $now), '1d 5h ago');
$t->is(time_ago_in_words_or_exact_date(strtotime('-8 day -5 hours', $now), $cutoff, $dt_f, $now), 'On Apr 1, 2012, 3:00:0 PM');