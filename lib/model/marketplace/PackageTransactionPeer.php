<?php

require 'lib/model/marketplace/om/BasePackageTransactionPeer.php';

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
      $priceWithDiscount = max(0, (float)$package->getPackagePrice() - $discount);

      $promoTransaction = PromotionTransactionPeer::newTransaction($collector, $promotion, $discount, $promotion->getAmountType());

      $transaction->setPromotionTransaction($promoTransaction);
      $transaction->setDiscount($discount); //Keep it here even if prices change
    }

    $transaction->setPackagePrice($priceWithDiscount);
    $transaction->setPaymentStatus($priceWithDiscount ? self::PAYMENT_STATUS_PENDING : self::PAYMENT_STATUS_PAID);
    $transaction->save();

    return $transaction;
  }


}
