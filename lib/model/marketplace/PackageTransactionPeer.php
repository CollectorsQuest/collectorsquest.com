<?php

require 'lib/model/marketplace/om/BasePackageTransactionPeer.php';

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

  /**
   * @static
   * @param Collector $collector
   * @param Package $package
   * @param null|Promotion $promotion
   * @return \PackageTransaction
   *
   * @todo unit tests
   */
  public static function newTransaction(Collector $collector, Package $package, Promotion $promotion = null)
  {
    $transaction = new PackageTransaction();
    $transaction->setCollector($collector);
    $transaction->setPackage($package);
    $transaction->setExpiryDate(strtotime('+1 year'));
    $transaction->setCredits($package->getCredits());

    $priceWithDiscount = $package->getPackagePrice();
    if (!is_null($promotion))
    {
      if (PromotionPeer::DISCOUNT_FIXED == $promotion->getAmountType())
      {
        $discount = (float)$promotion->getAmount();
        $discountTypeString = '$';
      }
      else
      {
        $discount = (float)($package->getPackagePrice() * $promotion->getAmount()) / 100;
        $discountTypeString = '%';
      }
      $priceWithDiscount = (float)$package->getPackagePrice() - $discount;

      $promoTransaction = PromotionTransactionPeer::newTransaction($collector, $promotion, $discount, $promotion->getAmountType());

      $transaction->setPromotionTransaction($promoTransaction);
      $transaction->setDiscount($discount); //Keep it here even if prices change
    }

    $transaction->setPackagePrice($priceWithDiscount);
    $transaction->setPaymentStatus($priceWithDiscount ? self::STATUS_PENDING : self::STATUS_PAID);
    $transaction->save();

    return $transaction;
  }


}
