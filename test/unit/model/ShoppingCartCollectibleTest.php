<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(17, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('::setPriceAmount(), ::getPriceAmount()');

  $object = new ShoppingCartCollectible();

  $object->setPriceAmount('156.64');
  $t->is($object->getPriceAmount(), $object->getPriceAmount('float'), 'Checking if "float" is the default return type');
  $t->is($object->getPriceAmount('float'), 156.64, 'Checking floats as string type');
  $t->is($object->getPriceAmount('integer'), 15664, 'Checking floats as string type');

  $object->setPriceAmount('1,156.64');
  $t->is($object->getPriceAmount('float'), 1156.64, 'Checking "comma" in floats as string type');
  $t->is($object->getPriceAmount('integer'), 115664, 'Checking "comma" in floats as string type');

  $object->setPriceAmount(156.65);
  $t->is($object->getPriceAmount('float'), 156.65, 'Checking floats');
  $t->is($object->getPriceAmount('integer'), 15665, 'Checking floats');

  $object->setPriceAmount(15666);
  $t->is($object->getPriceAmount('integer'), 15666, 'Checking integers');

  $object->setPriceAmount('15666');
  $t->is($object->getPriceAmount('integer'), 15666, 'Checking integers as string type');

  $object->setPriceAmount('156' * 1.00);
  $t->is($object->getPriceAmount('integer'), 15600, 'Checking multiplication of integers as string type with a float');

  $object->setPriceAmount('156.64' * 1.00);
  $t->is($object->getPriceAmount('integer'), 15664, 'Checking multiplication of float as string type with a float');

$t->diag('::setTaxAmount(), ::getTaxAmount()');

  $object = new ShoppingCartCollectible();

  $object->setTaxAmount('156.64');
  $t->is($object->getTaxAmount(), $object->getTaxAmount('float'), 'Checking if "float" is the default return type');
  $t->is($object->getTaxAmount('float'), 156.64, 'Checking floats as string type');
  $t->is($object->getTaxAmount('integer'), 15664, 'Checking floats as string type');

$t->diag('::setShippingFeeAmount(), ::getShippingFeeAmount()');

  $object = new ShoppingCartCollectible();

  $object->setShippingFeeAmount('156.64');
  $t->is($object->getShippingFeeAmount(), $object->getShippingFeeAmount('float'), 'Checking if "float" is the default return type');
  $t->is($object->getShippingFeeAmount('float'), 156.64, 'Checking floats as string type');
  $t->is($object->getShippingFeeAmount('integer'), 15664, 'Checking floats as string type');
