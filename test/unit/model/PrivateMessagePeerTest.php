<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(3, array('output' => new lime_output_color(), 'error_reporting' => true));

// Reset all tables we will be working on
cqTest::resetTables(array('private_message_template', 'private_message'));

$t->diag('::sendFromTemplate()');

  $message_template = new PrivateMessageTemplate();
  $message_template->setSubject('Welcome to CollectorsQuest, {collector.display_name}');
  $message_template->setBody('Hello, {collector.username}, we warmly <a href="{route.collections}">welcome</a> you to CQ.com!');
  $message_template->save();

  $options = array(
    'strtr' => array(
      '{collector.display_name}' => 'Kiril Angov',
      '{collector.username}' => 'KupoKoMaPa'
    )
  );
  $message = PrivateMessagePeer::sendFromTemplate($message_template, 2, 1, $options);

  $t->isa_ok($message, 'PrivateMessage');
  $t->is($message->getSubject(), 'Welcome to CollectorsQuest, Kiril Angov');
  $t->is($message->getBody(), 'Hello, KupoKoMaPa, we warmly <a href="{route.collections}">welcome</a> you to CQ.com!');

// Reset all tables we will be working on
cqTest::resetTables(array('message_template', 'private_message'));
