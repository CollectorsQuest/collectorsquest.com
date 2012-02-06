<?php

include(dirname(__FILE__).'/../../../bootstrap/model.php');

$t = new lime_test(3, new lime_output_color());

// setup
$wpPost = new wpPost();
$wpPost->setPostContent('<p><a href="whatever">Link</a> is cool.</p>');


// tests

$t->diag('->getPlainPostContent()');

  $t->is($wpPost->getPlainPostContent(), 'Link is cool.');


$t->diag('->countPostContentWords()');

  $t->is($wpPost->countPostContentWords(), 3);


$t->diag('->countPostContentChars()');

  $t->is($wpPost->countPostContentChars(), 11);