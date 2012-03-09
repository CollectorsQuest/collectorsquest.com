<?php

require 'lib/model/marketplace/om/BasePackageTransactionPeer.php';

class PackageTransactionPeer extends BasePackageTransactionPeer
{

  const STATUS_PAID = 'paid';
  const STATUS_PENDING = 'pending';

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
