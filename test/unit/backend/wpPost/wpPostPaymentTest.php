<?php

include(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(sfConfig::get('sf_root_dir').'/apps/backend/modules/wpPost/lib/wpPostPayment.class.php');
require_once(sfConfig::get('sf_plugins_dir').'/iceLibsPlugin/lib/vendor/Faker/autoload.php');
$faker = Faker\Factory::create();

$t = new lime_test(null, new lime_output_color());


// tests

$t->diag('::getUSDForWordCount()');

  $t->is(wpPostPayment::getUSDForWordCount(0), 10);
  $t->is(wpPostPayment::getUSDForWordCount(100), 10);
  $t->is(wpPostPayment::getUSDForWordCount(450), 25);
  $t->is(wpPostPayment::getUSDForWordCount(500), 25);


$t->diag('::getUSDForPost()');

  $wpPost = new wpPost();
  $wpPost->setPostContent($faker->sentence(10));
  $t->is(wpPostPayment::getUSDForPost($wpPost), 10);

  $wpPost->setPostContent($faker->sentence(500));
  $t->is(wpPostPayment::getUSDForPost($wpPost), 25);