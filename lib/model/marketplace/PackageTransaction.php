<?php

require 'lib/model/marketplace/om/BasePackageTransaction.php';

class PackageTransaction extends BasePackageTransaction
{
	/* added by Prakash Panchal 13-APR-2011
	 * savePackageTransaction function.
	 * return object
	 */
	public static function savePackageTransaction($amData = array())
	{
		$oPackageTransaction = new PackageTransaction();
		$oPackageTransaction->setCollectorId($amData['collector_id']);
		$oPackageTransaction->setPackageId($amData['package_id']);
		$oPackageTransaction->setMaxItemsForSale($amData['max_items_for_sale']);
		$oPackageTransaction->setPackagePrice($amData['package_price']);
		$oPackageTransaction->setExpiryDate(date('Y-m-d h:i:s', strtotime('+365 days')));
		$oPackageTransaction->setPaymentStatus($amData['payment_status']);

		try
		{
			$oPackageTransaction->save();
		}
		catch (PropelException $e)
		{
			return null;
		}

		return $oPackageTransaction;
	}

	/* added by Prakash Panchal 14-APR-2011
	 * updatePaymentStatus function.
	 * return object
	 */
	public static function updatePaymentStatus($amData = array())
	{
		$oPackageTransaction = PackageTransactionPeer::retrieveByPK($amData['id']);
		$oPackageTransaction->setPackagePrice($amData['package_price']);
		$oPackageTransaction->setPaymentStatus($amData['payment_status']);
		try
		{
			$oPackageTransaction->save();
		}
		catch (PropelException $e)
		{
			return null;
		}

		return $oPackageTransaction;
	}

  /**
   * @return PackageTransaction
   * @todo add tests
   */
  public function confirmPayment()
  {
    $this->setPaymentStatus(PackageTransactionPeer::STATUS_PAID);
    $this->save();

    /* @var $collector Collector */
    $collector = $this->getCollector();
    $collector->setUserType(CollectorPeer::TYPE_SELLER);
    $collector->updateCollectiblesForSaleLimit();
    $collector->save();

    return $this;
  }
}
