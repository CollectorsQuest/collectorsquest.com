<?php
$app = 'frontend';
require_once dirname(__FILE__) . '/../bootstrap/unit.php';
require_once dirname(__FILE__) . '/fixtures/testMailer.class.php';


$t = new lime_test();
$t->diag('Testing cqEmailsPlugin/lib/cqEmail.class.php');

$mailer = new TestMailer(array(
  'delivery_strategy' => 'spool',
  'spool_class'       => 'TestSpool',
  'spool_arguments'   => array('TestMailMessage'),
  'transport'         => array('class' => 'TestMailerTransport'),
));


$cqEmail = new cqEmail($mailer);
$t->ok($mailer === $cqEmail->getMailer());


try{
  $cqEmail->send('NonExisting');
  $t->fail('->send() throws InvalidArgumentException when called with non-existing template name');
} catch (InvalidArgumentException $e) {
  $t->pass('->send() throws InvalidArgumentException when called with non-existing template name');
}


try{
  $cqEmail->send('Collector/successful_registration');
  $t->fail('->send() throws InvalidArgumentException when called with missing mandatory params');
} catch (InvalidArgumentException $e) {
  $t->pass('->send() throws InvalidArgumentException when called with missing mandatory params');
}


try{
  $cqEmail->send('Collector/successful_registration', array('to'=>'vankata@example.com',  'params' => array('collector' => 'Pesho')));
  $t->pass('->send() works as expected with the proper input');
} catch (InvalidArgumentException $e) {
  $t->diag($e);
  $t->fail('->send() works as expected with the proper input');
}