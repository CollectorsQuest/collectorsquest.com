<?php

class cqPropelRoute extends sfPropelRoute
{
  protected function getObjectForParameters($parameters)
  {
    /** @var $object BaseObject */
    $object = parent::getObjectForParameters($parameters);

    if (@$this->options['statsd'] === true && method_exists($object, 'getId'))
    {
      /**
       * To avoid many objects in the same folder (graphite stats are stored in folders)
       * we create an easy subfolder structure like this:
       *
       * Object with ID 69070: collectorsquest.views.object.6.9.9070
       * Object with ID 9070:  collectorsquest.views.object.0.9.9070
       * Object with ID 7:     collectorsquest.views.object.0.0.7
       */
      $id = $object->getId();
      $path = 'collectorsquest.views.'. sfInflector::underscore(get_class($object));
      $path .= '.'. floor($id / 10000) .'.'. floor(($id % 10000) / 1000) .'.'. $id;

      // Increment the number of views
      cqStats::increment($path);
    }

    return $object;
  }
}
