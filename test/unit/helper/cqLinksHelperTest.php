<?php

$app = 'frontend';
include(__DIR__.'/../../bootstrap/functional.php');

$sf_configuration = sfApplicationConfiguration::getActive();
$sf_configuration->loadHelpers('cqLinks');

$t = new lime_test(8, array('output' => new lime_output_color(), 'error_reporting' => true));


$t->diag('cq_link_to()');

  $t->is(cq_link_to('Home', '@homepage'),
    '<a href="http://www.example.com/">Home</a>');
  $t->is(cq_link_to('Home', array('sf_route' => 'homepage')),
    '<a href="http://www.example.com/">Home</a>');
  $t->is(cq_link_to('Home', '@homepage', array('class' =>'test')),
    '<a class="test" href="http://www.example.com/">Home</a>');
  $t->is(cq_link_to('Messages', '@messages_inbox'),
    '<a class="requires-login" href="http://www.example.com/messages/inbox">Messages</a>');
  $t->is(cq_link_to('Messages', '@messages_inbox', array('class' => 'test')),
    '<a class="test requires-login" href="http://www.example.com/messages/inbox">Messages</a>');
  $t->is(cq_link_to('Messages', 'messages/inbox', array('class' => 'test')),
    '<a class="test requires-login" href="http://www.example.com/messages/inbox">Messages</a>');
  $t->is(cq_link_to('Messages', array('sf_route' => 'messages_inbox'), array('class' => 'test')),
    '<a class="test requires-login" href="http://www.example.com/messages/inbox">Messages</a>');
  $t->is(cq_link_to('Messages', array('sf_route' => 'messages_inbox'), array('class' => 'test', 'absolute' => false)),
    '<a class="test requires-login" href="/messages/inbox">Messages</a>');
