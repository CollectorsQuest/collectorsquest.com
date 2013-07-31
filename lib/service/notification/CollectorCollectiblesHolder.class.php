<?php

/**
 * Description of ExpiringCollectiblesHolder
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class CollectorCollectiblesHolder implements ArrayAccess, Countable, IteratorAggregate
{
    /** @var Collector */
    protected $collector;

    protected $collectibles;

    public function __construct(Collector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @return  Collector
     */
    public function getCollector()
    {
        return $this->collector;
    }

    // Interface implementations

    public function offsetExists($offset)
    {
       return isset($this->collectibles[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->collectibles[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->collectibles[] = $value;
        } else {
            $this->collectibles[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->collectibles[$offset]);
    }

    public function count()
    {
        return count($this->collectibles);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collectibles);
    }
}
