<?php
include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../lib/collectorsquest/cqBaseUser.class.php');

$t = new lime_test(11, array('output' => new lime_output_color(), 'error_reporting' => true));

$_SERVER['session_id'] = 'test';

$dispatcher = new sfEventDispatcher();
$sessionPath = sys_get_temp_dir().'/sessions_'.rand(11111, 99999);
$storage = new sfSessionTestStorage(array('session_path' => $sessionPath));

$user = new cqBaseUser($dispatcher, $storage);


$t->diag('->get/regenerateHmacSecret()');
  $t->is($user->hasAttribute('secret', 'hmac'), false);
  $user->regenerateHmacSecret();
  $t->is($user->hasAttribute('secret', 'hmac'), true);

  $user->setAttribute('secret', null, 'hmac');
  $t->is($user->hasAttribute('secret', 'hmac'), false);
  $t->isnt($secret = $user->getHmacSecret(), null);
  $t->is($secret, $user->getHmacSecret());


$t->diag('->hmacSignMessage() and ->hmacVerifyMessage()');
  $message = $user->hmacSignMessage('Test message');

  $t->is($user->hmacVerifyMessage($message), 'Test message',
    'hmac message is verified successfully');
  $t->is_deeply($user->hmacVerifyMessage($message, '-10 minutes'), false,
    'hmac timeout works as expected');


  $data = json_decode($message, true);
  $data['message'] = 'Fake message';
  $t->is_deeply($user->hmacVerifyMessage(json_encode($data)), false,
    'replacing the message component fails the verification');

  $data = json_decode($message, true);
  $data['time'] = '1321312312';
  $t->is_deeply($user->hmacVerifyMessage(json_encode($data)), false,
    'replacing the time component fails the verification');

  $data = json_decode($message, true);
  $data['hmac'] = sha1(time());
  $t->is_deeply($user->hmacVerifyMessage(json_encode($data)), false,
    'replacing the hmac component fails the verification');

  $user->regenerateHmacSecret();
  $t->is_deeply($user->hmacVerifyMessage(json_encode($data)), false,
    'regenerating the user hmac session will cause all messages encoded with the old secret to fail verification');
