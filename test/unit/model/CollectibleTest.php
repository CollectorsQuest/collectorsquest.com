<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(6, array(new lime_output_color()));

cqTest::resetTables(array('collectible', 'collectible_archive'));

$t->diag('::setName()');

  $collectible = new Collectible();
  $collectible->setName('<b>Bad Code</b> Do not allow <h2>Also</h3>');
  $t->is($collectible->getName(), 'Bad Code Do not allow Also');

$t->diag('::setDescription()');

  $collectible = new Collectible();
  $collectible->setDescription('<p>An <a href="http://example.com/" title="Title">example</a>. Then, anywhere else in the doc, define the link:</p>', 'html');
  $t->is($collectible->getDescription('markdown'), 'An example. Then, anywhere else in the doc, define the link:');
  $t->is($collectible->getDescription('html'), "<p>An example. Then, anywhere else in the doc, define the link:</p>\n");

  $collectible = new Collectible();
  $collectible->setDescription('<h1>Header 1</h1><h2>Header 2</h2><h6>Header 6</h6>', 'html');
  $t->is(str_replace("\n", ' ', $collectible->getDescription('markdown')), '# Header 1  ## Header 2  ###### Header 6');
  $t->is($collectible->getDescription('html'), "<h1>Header 1</h1>\n\n<h2>Header 2</h2>\n\n<h6>Header 6</h6>\n");

$t->diag('Setting and getting the slug');

  $collectible = new Collectible();
  $collectible->setCollectorId(1);
  $collectible->setName('Untitled Item');
  $collectible->setDescription('No description required here');
  $collectible->save();

  $collectible->delete();

  $collectible = new Collectible();
  $collectible->setCollectorId(1);
  $collectible->setName('Untitled Item');
  $collectible->setDescription('No description required here also');
  $collectible->save();

  $t->like($collectible->getSlug(), '/untitled-item-\w+/i');
