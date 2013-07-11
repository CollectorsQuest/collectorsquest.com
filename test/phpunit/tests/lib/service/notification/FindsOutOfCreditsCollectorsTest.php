<?php

// tested classes
require_once get_root_dir() . '/lib/service/notification/FindsOutOfCreditsCollectors.class.php';

class FindsOutOfCreditsCollectorsTest extends sfWebTestCase
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
        $this->finder = new FindsOutOfCreditsCollectors();
    }

    public function test_it_finds_ran_outs_for_date()
    {
        $date = new DateTime('-1 month');
        $ran_outs = $this->finder->findRanOutOn($date);
        $this->assertCount(0, $ran_outs);


        $u_ran_out = CollectorPeer::retrieveByUsername('ran_out');
        $date = new DateTime('-1 week');
        $ran_outs = $this->finder->findRanOutOn($date);
        $this->assertCount(1, $ran_outs);
        $this->assertArrayHasKey($u_ran_out->getId(), $ran_outs);
        $this->assertSame($ran_outs[$u_ran_out->getId()], $u_ran_out);
    }

}