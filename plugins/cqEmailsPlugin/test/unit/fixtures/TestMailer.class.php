<?php

require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/swiftmailer/classes/Swift.php';
Swift::registerAutoload();
sfMailer::initialize();
require_once dirname(__FILE__).'/TestMailerTransport.class.php';
require_once dirname(__FILE__).'/TestSpool.class.php';
require_once dirname(__FILE__).'/TestMailMessage.class.php';

class TestMailer extends sfMailer
{
  public function __construct($options)
  {
    parent::__construct(new sfEventDispatcher(), $options);
  }
}