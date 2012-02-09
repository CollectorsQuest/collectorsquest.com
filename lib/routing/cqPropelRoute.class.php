<?php

class cqPropelRoute extends sfPropelRoute
{
  protected function getObjectForParameters($parameters)
  {
    /** @var $object BaseObject */
    $object = parent::getObjectForParameters($parameters);

    if (isset($this->options['statsd']) && true == $options['statsd'])
    {
      cqStatsLogger::viewPropelObject($object);
    }

    return $object;
  }

}
