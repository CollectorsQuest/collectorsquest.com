<?php
/**
 * A minimalistic phpUnit bootstrap that just defines some basic vars
 */

function get_test_dir() {
  return realpath(__DIR__);
}

function get_root_dir() {
  return realpath(get_test_dir() . '/../..');
}