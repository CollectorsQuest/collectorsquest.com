<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(0, new lime_output_color());

$t->diag('::fileExists()');
