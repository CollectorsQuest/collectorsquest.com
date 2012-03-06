<?php

require_once __DIR__.'/../../bootstrap/unit.php';
require_once __DIR__.'/../../bootstrap/routing.php';

$t = new lime_test(5, array('output' => new lime_output_color(), 'error_reporting' => true));

  /** @var $sf_application sfApplicationConfiguration */
  $sf_application->loadHelpers('Url');

  $routing = new cqPatternRouting($sf_context->getEventDispatcher());
  //$sf_context->set('routing', $routing);

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&encrypt=1', false);
  $t->is(substr($url, 0, 7), '/ex/v1;', 'Testing the passing of GET parameters with encryption');

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&encrypt=1', true);
  $t->is(substr($url, 0, 7), 'http://', 'Testing the passing of GET parameters with encryption and absolute');

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&encrypt=1', false);
  $t->is($routing->decryptUrl($url), 'test/unit/cq/collection/1/slideonover', 'Testing the decryption');

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&encrypt=1', false);
  $_SERVER['QUERY_STRING'] = 'token=EC-22W34314DU597432P&PayerID=84BQLBU43QAB8';
  $t->is(
    $routing->decryptUrl($url),
    'test/unit/cq/collection/1/slideonover',
    'Testing the decryption with extra query string'
  );

  $url = url_for('@collection_by_slug?id=1&slug=slideonover&cmd=return&encrypt=1', false);
  $_SERVER['QUERY_STRING'] = 'token=EC-22W34314DU597432P&PayerID=84BQLBU43QAB8';
  $t->is(
    $routing->decryptUrl($url),
    'test/unit/cq/collection/1/slideonover',
    'Testing the decryption with extra query string'
  );
