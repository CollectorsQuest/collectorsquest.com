<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(5, new lime_output_color());

$t->diag('::setName()');

  $collection = new Collection();
  $collection->setName('<b>Bad Code</b> Do not allow <h2>Also</h3>');
  $t->is($collection->getName(), 'Bad Code Do not allow Also');

$t->diag('::setDescription()');

  $collection = new Collection();
  $collection->setDescription('<p>An <a href="http://example.com/" title="Title">example</a>. Then, anywhere else in the doc, define the link:</p>', 'html');
  $t->is($collection->getDescription('markdown'), 'An example. Then, anywhere else in the doc, define the link:');
  $t->is($collection->getDescription('html'), "<p>An example. Then, anywhere else in the doc, define the link:</p>\n");

  $collection = new Collection();
  $collection->setDescription('<h1>Header 1</h1><h2>Header 2</h2><h6>Header 6</h6>', 'html');
  $t->is(str_replace("\n", ' ', $collection->getDescription('markdown')), '# Header 1  ## Header 2  ###### Header 6');
  $t->is($collection->getDescription('html'), "<h1>Header 1</h1>\n\n<h2>Header 2</h2>\n\n<h6>Header 6</h6>\n");
