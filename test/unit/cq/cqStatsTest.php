<?php

include __DIR__ . '/../../bootstrap/unit.php';
include __DIR__ . '/../mock/cqStatsMock.php';

$t = new lime_test(5, array(new lime_output_color()));
$t->diag('Testing cqStats');

$t->diag('::view()');

  $collection = new Collection();
  $tests = array(
    7 => '0.0.7',
    10 => '0.0.10',
    9070 => '0.9.9070',
    69070 => '6.9.69070',
    669070 => '66.9.669070',
  );

  foreach ($tests as $id => $tail)
  {
    $result_stats = 'collection.' . $tail;
    $collection->setId($id);

    //$t->ok(cqStatsMock::viewPropelObject($collection), 'Tracking the view');
    $t->is(cqStatsMock::hasStat($result_stats), $result_stats);
  }
