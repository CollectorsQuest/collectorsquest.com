<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(1, new lime_output_color());

$t->diag('::setEmail()');

  $collector = CollectorPeer::doSelectOne(new Criteria());
  $collector->setEmail('kangov@collectorsquest.com');
  $t->is($collector->getEmail(), 'kangov@collectorsquest.com');
