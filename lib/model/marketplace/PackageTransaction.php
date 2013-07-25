<?php

require 'lib/model/marketplace/om/BasePackageTransaction.php';

class PackageTransaction extends BasePackageTransaction
{
  public function getPackageName()
  {
    if (( $package = $this->getPackage() ))
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

  /**
   * Check if the package transaction is expired, or if it is expiring based on
   * strtotime argument
   *
   * @param     string $within strtotime argument if you need to check +/- few days
   * @param     integer $now Manually set current time for this function
   *
   * @return    boolean
   */
  public function isExpired($within = '0 days', $now = null)
  {
    $compare = strtotime($within, null === $now ? time() : $now);
    $compare = DateTime::createFromFormat('U', $now);

    return $this->getExpiryDate(null)->format('Ymd') <= $compare->format('Ymd');
  }

}
