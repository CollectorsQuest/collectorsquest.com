<?php

// tested classes
require_once get_root_dir() . '/lib/service/notification/ExpiringCollectibleForSaleNotifier.class.php';

class ExpiringCollectibleForSaleNotifierTest extends sfWebTestCase
{
    /** @var cqMail */
    protected $mailer;
    /** @var ExpiringCollectibleForSaleNotifier */
    protected $notifier;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::loadFixtureDirs(array(
            '01_test_collectors',
            '03_test_collectibles',
        ));
    }

    public function setUp()
    {
        $transport = $this->getMock('Swift_MailTransport');
        $swift = $this->getMock('Swift_Mailer', array(), array($transport));
        $this->mailer = $this->getMock('cqEmail', array('send'), array($swift));
        $this->notifier = new ExpiringCollectibleForSaleNotifier($this->mailer);
    }

    public function test_generates_expires()
    {
        $expires = $this->notifier->getExpiringIn('+1 week');
        $this->assertSame(array(), $expires);

        $ivan = CollectorPeer::retrieveByUsername('ivan.ivanov');

        $expires = $this->notifier->getExpiringIn('+1 day -1 hour');
        $this->assertArrayHasKey($ivan->getId(), $expires);
        $this->assertCount(1, $expires[$ivan->getId()]['collectibles']);
    }



}