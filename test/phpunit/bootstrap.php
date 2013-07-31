<?php
/**
 * A minimalistic phpUnit bootstrap that just defines some basic vars
 */

require_once get_test_dir() . '/lib/test_case/sfFunctionalTestCase.php';
require_once get_test_dir() . '/lib/test_case/sfWebTestCase.php';

function get_test_dir() {
  return realpath(__DIR__);
}

function get_root_dir() {
  return realpath(get_test_dir() . '/../..');
}