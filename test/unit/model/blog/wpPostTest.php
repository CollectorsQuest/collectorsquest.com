<?php

include(dirname(__FILE__).'/../../../bootstrap/model.php');

$t = new lime_test(5, new lime_output_color());


// tests

$t->diag('->getPlainPostContent()');

  $wpPost = new wpPost();
  $wpPost->setPostContent('  <p><a href="whatever">Link</a> is cool.</p>  ');

  $t->is($wpPost->getPlainPostContent(), 'Link is cool.');


$t->diag('->countPostContentWords()');

  $t->is($wpPost->countPostContentWords(), 3);
  $wpPost2 = new wpPost();
  $wpPost2->setPostContent('');
  $t->is($wpPost2->countPostContentWords(), 0);


$t->diag('->countPostContentChars()');

  $t->is($wpPost->countPostContentChars(), 11);
  $t->is($wpPost2->countPostContentChars(), 0);