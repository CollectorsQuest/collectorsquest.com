<?php

include(__DIR__.'/../../../bootstrap/model.php');

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('Testing PackageTransaction.class.php');

$t->diag('Test isExpired()');
  $pt = new PackageTransaction();
  $pt->setExpiryDate(strtotime('2012-12-12'));
  $t->ok(!$pt->isExpired('0 days', strtotime('2012-12-11')));
  $t->ok($pt->isExpired('0 days', strtotime('2012-12-15')));
  $t->ok($pt->isExpired('5 days', strtotime('2012-12-11')));
  $t->ok($pt->isExpired('2 days', strtotime('2012-12-11')));