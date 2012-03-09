<?php

require 'lib/model/om/BasePackageTransactionPeer.php';

class PackageTransactionPeer extends BasePackageTransactionPeer
{

  const STATUS_PAID = 'paid';
  const STATUS_PENDING = 'pending';
  const STATUS_CANCELED = 'canceled';

  /**
   * @static
   * @param $collectorId
   * @return PackageTransaction
   */
  public static function checkExpiryDate($collectorId)
	{
		$criteria = new Criteria();
		$criteria->add(PackageTransactionPeer::COLLECTOR_ID, $collectorId);
		$criteria->addDescendingOrderByColumn(PackageTransactionPeer::ID);

		return PackageTransactionPeer::doSelectOne($criteria);
	}
}
