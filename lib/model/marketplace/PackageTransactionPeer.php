<?php

class PackageTransactionPeer extends BasePackageTransactionPeer
{

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

    if (!is_null($promotion))
    {
      $package->applyPromo($promotion);
      $promoTransaction = PromotionTransactionPeer::newTransaction(
        $collector, $promotion, $package->getDiscount(), $promotion->getAmountType()
      );

      $transaction->setPromotionTransaction($promoTransaction);
      $transaction->setDiscount($package->getDiscount()); //Keep it here even if prices change
    }
    $priceWithDiscount = $package->getPriceWithDiscount($promotion);

    $transaction->setPackagePrice($priceWithDiscount);
    $transaction->setPaymentStatus(
      $priceWithDiscount ? self::PAYMENT_STATUS_PENDING : self::PAYMENT_STATUS_PAID
    );
    $transaction->save();

    return $transaction;
  }

}
