<?php

require 'lib/model/om/BaseCollectorEmailPeer.php';

class CollectorEmailPeer extends BaseCollectorEmailPeer
{

  /**
   * @static
   *
   * @param  Collector|integer  $collector
   * @param  string   $email
   * @param  boolean  $verified
   *
   * @return CollectorEmail
   */
  public static function retrieveByCollectorEmail($collector, $email, $verified = null)
  {
    $collectorId = $collector instanceof Collector ? $collector->getId() : $collector;

    /** @var $q CollectorEmailQuery */
    $q = CollectorEmailQuery::create()
       ->filterByCollectorId($collectorId)
       ->filterByEmail($email);

    if (null !== $verified)
    {
      $q->filterByIsVerified((bool)$verified);
    }

    return $q->findOne();
  }

}
