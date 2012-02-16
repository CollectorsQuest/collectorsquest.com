<?php


require 'lib/model/om/BaseCollectorEmailPeer.php';

/**
 * Skeleton subclass for performing query and update operations on the 'collector_email' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class CollectorEmailPeer extends BaseCollectorEmailPeer
{

  public static function retrieveByCollectorEmail($collector, $email, $verified = null)
  {
    $collectorId = $collector instanceof Collector ? $collector->getId() : $collector;

    $query = CollectorEmailQuery::create()
        ->filterByCollectorId($collectorId)
        ->filterByEmail($email);

    if (!is_null($verified))
    {
      $query->filterByIsVerified((bool)$verified);
    }

    return $query->findOne();
  }

}
