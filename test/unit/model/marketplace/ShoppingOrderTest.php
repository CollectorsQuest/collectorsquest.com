<?php

include(__DIR__.'/../../../bootstrap/model.php');

$t = new lime_test(0, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('::save()');

