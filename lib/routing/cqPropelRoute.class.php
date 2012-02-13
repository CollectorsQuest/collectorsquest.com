<?php

class cqPropelRoute extends sfPropelRoute
{

  protected function getObjectForParameters($parameters)
  {
    /** @var $object BaseObject */
    $object = parent::getObjectForParameters($parameters);

    if (isset($this->options['statsd']) && $this->options['statsd'] == true)
    {
      cqStatsLogger::viewPropelObject($object);
    }

    return $object;
  }

}
