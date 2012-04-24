<?php

include __DIR__ . '/../../bootstrap/unit.php';

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing cqStatic');

$t->isa_ok(cqStatic::getPayPalClient(), 'PayPal');
