<?php

// tested classes
require_once get_root_dir() . '/lib/service/notification/FindsExpiringCollectibles.class.php';

class FindsExpiringCollectiblesTest extends sfWebTestCase
{
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
        $this->finder = new FindsExpiringCollectibles();
    }

    public function test_it_finds_expires_for_date()
    {
        $date = new DateTime('+1 week');
        $expires = $this->finder->findExpiringOn($date);
        $this->assertSame(array(), $expires);

        $ivan = CollectorPeer::retrieveByUsername('ivan.ivanov');

        $date = new DateTime('+1 day -1 hour');
        $expires = $this->finder->findExpiringOn($date);
        $this->assertArrayHasKey($ivan->getId(), $expires);
        $holder = $expires[$ivan->getId()];
        $this->assertInstanceOf('ExpiringCollectiblesHolder', $holder);
        $this->assertCount(1, $holder);
        $this->assertSame($date, $holder->getExpireDate());
    }

}