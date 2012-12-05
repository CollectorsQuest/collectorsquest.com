<?php

require 'lib/model/marketplace/om/BasePackageTransaction.php';

class PackageTransaction extends BasePackageTransaction
{
  public function getPackageName()
  {
    if ($package = $this->getPackage())
    {
      return $package->getPackageName();
    }

    return null;
  }

  /**
   * @return PackageTransaction
   * @todo add tests
   */
  public function confirmPayment()
  {
    $this->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID);
    $this->save();

    /* @var $collector Collector */
    $collector = $this->getCollector();
    $collector->setUserType(CollectorPeer::TYPE_SELLER);
    $collector->save();

    return $this;
  }

  /**
   * @return    integer
   */
  public function getCreditsRemaining()
  {
    return $this->getCredits() - $this->getCreditsUsed();
  }

}
