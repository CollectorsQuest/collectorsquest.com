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
   * @param  PropelPDO $con
   *
   * @return CollectorEmail
   */
  public static function retrieveByCollectorEmail($collector, $email, $verified = null, PropelPDO $con = null)
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

    return $q->findOne($con);
  }

  /**
   * Retrieve last collector email change request
   *
   * @static
   * @param Collector|int $collector
   * @param bool $verified
   *
   * @return CollectorEmail
   */
  public static function retrieveLastPending($collector, $verified = false)
  {
    $collectorId = $collector instanceof Collector ? $collector->getId() : $collector;

    return CollectorEmailQuery::create()
        ->filterByCollectorId($collectorId)
        ->filterByIsVerified($verified)
        ->orderById('desc')
        ->findOne();
  }
}
