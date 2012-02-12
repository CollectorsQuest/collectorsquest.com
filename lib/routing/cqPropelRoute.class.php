<?php

class cqPropelRoute extends sfPropelRoute
{

  protected function getObjectForParameters($parameters)
  {
    /** @var $object BaseObject */
    $object = parent::getObjectForParameters($parameters);

    if (isset($this->options['statsd']) and true == $this->options['statsd'])
    {
      cqStatsLogger::viewPropelObject($object);
    }

    return $object;
  }

}
