<?php

class cqPropelRoute extends sfPropelRoute
{

  protected function getObjectForParameters($parameters)
  {
    /** @var $object BaseObject */
    $object = parent::getObjectForParameters($parameters);

    /**
     * @todo Recording of many stats in Graphite takes 3.2mb per entry by default!
     *       We need to figure out a better way to store those views in Graphite.
     */
    if (false && isset($this->options['statsd']) && $this->options['statsd'] == true)
    {
      cqStats::viewPropelObject($object);
    }

    return $object;
  }

  public function getCanonicalUrl()
  {
    $object = $this->getObject();

    if ($object instanceof CollectionCollectible || $object instanceof Collectible)
    {
      $canonical_url = url_for(route_for_collectible($object), $object, true);
    }
    else
    {
      $canonical_url = url_for(sfContext::getInstance()->getRouting()->getCurrentRouteName(), $object, true);
    }

    return $canonical_url;
  }

}
