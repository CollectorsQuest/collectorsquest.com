<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
include(sfConfig::get('sf_test_dir').'/unit/mock/cqStatsMock.php'); // for mock cqStats class
include(sfConfig::get('sf_lib_dir').'/log/cqStatsLogger.class.php');

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing cqStatsLogger');


$t->diag('::viewPropelObject()');

  $coll = new Collection();
  $tests = array(
      7 => '0.0.7',
      10 => '0.0.10',
      9070 => '0.9.9070',
      69070 => '6.9.69070',
      669070 => '66.9.669070',
  );

  foreach ($tests as $id => $tail)
  {
    $result_stats = 'collectorsquest.views.collection.' . $tail;
    $coll->setId($id);

    cqStatsLogger::viewPropelObject($coll);
    $t->ok(cqStats::hasStat($result_stats), $result_stats);
  }