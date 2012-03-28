<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(7, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('::getUuidFromId()');

  $t->is(ShoppingOrderPeer::getUuidFromId('1000001'), 'EK00X86V');
  $t->is(ShoppingOrderPeer::getUuidFromId('1000101'), 'EK00P92Y');
  $t->is(ShoppingOrderPeer::getUuidFromId('1001001'), 'EK03PXMA');
  $t->is(ShoppingOrderPeer::getUuidFromId('1010001'), 'EK33VVBQ');
  $t->is(ShoppingOrderPeer::getUuidFromId('1100001'), 'EA3JRYT8');
  $t->is(ShoppingOrderPeer::getUuidFromId('1234567'), 'EH7XEK8R');
  $t->is(ShoppingOrderPeer::getUuidFromId('7654321'), '1D68ONRI');
