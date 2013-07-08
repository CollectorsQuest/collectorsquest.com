<?php

// tested classes
require_once get_root_dir() . '/lib/service/notification/CollectorCollectiblesHolder.class.php';

class CollectorCollectiblesHolderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->collector = $this->getMock('Collector');
        $this->holder = new CollectorCollectiblesHolder($this->collector);

        $this->collectible1 = $this->getMock('Collectible');
        $this->collectible2 = $this->getMock('Collectible');
    }

    public function test_it_can_be_initialized()
    {
        $holder = new CollectorCollectiblesHolder($this->collector);
        $this->assertInstanceOf('CollectorCollectiblesHolder', $holder);
    }

    public function test_collector_getter()
    {
        $this->assertSame($this->collector, $this->holder->getCollector());
    }

    public function test_array_access()
    {
        $this->holder[] = $this->collectible1;
        $this->holder[] = $this->collectible2;

        $this->assertSame($this->collectible1, $this->holder[0]);
        $this->assertCount(2, $this->holder);
    }

    public function test_array_iterate()
    {
        $this->holder[] = $this->collectible1;
        $this->holder[] = $this->collectible2;

        $items = 0;
        foreach ($this->holder as $collectible)
        {
            $items++;
        }

        $this->assertEquals(2, $items);
    }

    public function test_array_count()
    {
        $this->holder[] = $this->collectible1;
        $this->holder[] = $this->collectible2;


        $this->assertCount(2, $this->holder);
    }
}