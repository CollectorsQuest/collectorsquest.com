<?php

require_once __DIR__.'/../../bootstrap/unit.php';
require_once __DIR__.'/../../bootstrap/routing.php';

$t = new lime_test(2, array('output' => new lime_output_color(), 'error_reporting' => true));

  /** @var $sf_application sfApplicationConfiguration */
  $sf_application->loadHelpers('Url');

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&encrypt=1', false);
  $t->is(substr($url, 0, 7), '/ex/v1;', 'Testing the passing of GET parameters with encryption');

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&encrypt=1', true);
  $t->is(substr($url, 0, 18), 'http://test/ex/v1;', 'Testing the passing of GET parameters with encryption and absolute');
