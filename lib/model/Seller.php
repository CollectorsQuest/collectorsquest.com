<?php

/**
 * A wrapper object around Collector
 *
 * @method     int getSingupNumCompletedSteps() Return the number of completed signup steps
 * @method     Collector setSingupNumCompletedSteps(int $v) Set the number of completed signup steps
 * @method     Collector setCqnextAccessAllowed(boolean $v)
 *
 *
 * @method     Collector setSellerSettingsPaypalAccountId(string $v)
 * @method     string    getSellerSettingsPaypalAccountId()
 *
 * @method     Collector setSellerSettingsPaypalAccountStatus(string $v)
 * @method     string    getSellerSettingsPaypalAccountStatus()
 *
 * @method     Collector setSellerSettingsPaypalBusinessName(string $v)
 * @method     string    getSellerSettingsPaypalBusinessName()
 *
 * @method     Collector setSellerSettingsPaypalEmail(string $v)
 * @method     string    getSellerSettingsPaypalEmail()
 *
 * @method     Collector setSellerSettingsPaypalFirstName(string $v)
 * @method     string    getSellerSettingsPaypalFirstName()
 *
 * @method     Collector setSellerSettingsPaypalLastName(string $v)
 * @method     string    getSellerSettingsPaypalLastName()
 *
 *
 * @method     Collector setSellerSettingsPhoneCode(string $v)
 * @method     string    getSellerSettingsPhoneCode()
 *
 * @method     Collector setSellerSettingsPhoneNumber(string $v)
 * @method     string    getSellerSettingsPhoneNumber()
 *
 * @method     Collector setSellerSettingsPhoneExtension(string $v)
 * @method     string    getSellerSettingsPhoneExtension()
 *
 * @method     Collector setSellerSettingsStoreName(string $v)
 * @method     string    getSellerSettingsStoreName()
 *
 * @method     Collector setSellerSettingsStoreTitle(string $v)
 * @method     string    getSellerSettingsStoreTitle()
 *
 * @method     Collector setSellerSettingsStoreDescription(string $v)
 * @method     string    getSellerSettingsStoreDescription()
 *
 * @method     Collector setSellerSettingsReturnPolicy(string $v)
 * @method     string    getSellerSettingsReturnPolicy()
 *
 * @method     Collector setSellerSettingsPaymentAccepted(string $v)
 * @method     string    getSellerSettingsPaymentAccepted()
 *
 * @method     Collector setSellerSettingsWelcome(string $v)
 * @method     string    getSellerSettingsWelcome()
 *
 * @method     Collector setSellerSettingsShipping(string $v)
 * @method     string    getSellerSettingsShipping()
 *
 * @method     Collector setSellerSettingsRefunds(string $v)
 * @method     string    getSellerSettingsRefunds()
 *
 * @method     Collector setSellerSettingsAdditionalPolicies(string $v)
 * @method     string    getSellerSettingsAdditionalPolicies()
 *
 *
 * @method     Collector setVisitorInfoNumVisits(int $v)
 * @method     int       getVisitorInfoNumVisits()
 *
 * @method     Collector setVisitorInfoNumPageViews(int $v)
 * @method     int       getVisitorInfoNumPageViews()
 */
class Seller
{

  /** @var  Collector */
  protected $collector;

  public function __construct(Collector $collector)
  {
    $this->collector = $collector;
  }

  /**
   * Forward all undefined method calls to the wrapped Collector object
   *
   * @param     string $name
   * @param     array $arguments
   *
   * @return    mixed
   */
  public function __call($name, $arguments)
  {
    return call_user_func_array(array($this->collector, $name), $arguments);
  }

  /**
   * Get the wrapped Collector object
   *
   * @return    Collector
   */
  public function getCollector()
  {
    return $this->collector;
  }

  /**
   * The PayPal business name
   *
   * @return string
   */
  public function getBusinessName()
  {
    return $this->collector->getSellerSettingsPaypalBusinessName();
  }

  /**
   * The collector's full name, as registered with PayPal
   *
   * @return    string
   */
  public function getFullName()
  {
    return implode(' ', array(
      $this->collector->getSellerSettingsPaypalFirstName(),
      $this->collector->getSellerSettingsPaypalLastName()
    ));
  }

  /**
   * @return    string
   */
  public function getPayPalEmail()
  {
    return $this->collector->getSellerSettingsPaypalEmail();
  }

  /**
   * Check if current user has credits left
   *
   * @return bool
   */
  public function hasPackageCredits()
  {
    return 0 < $this->getCreditsLeft();
  }

  /**
   * Retrieve total number of credits for active packages for the current user
   *
   * @return integer
   *
   * @todo unit tests
   */
  public function getPackageCreditsSum()
  {
    return (integer) PackageTransactionQuery::create()
      ->filterByCollector($this->collector)
      ->paidFor()
      ->notExpired()
      ->withColumn('SUM(PackageTransaction.Credits)', 'CreditsTotal')
      ->select('CreditsTotal')
      ->findOne();
  }

  /**
   * Retrieve number of seller credits left for use
   *
   * @return integer
   */
  public function getCreditsLeft()
  {
    return (int) PackageTransactionQuery::create()
      ->filterByCollector($this->collector)
      ->paidFor()
      ->notExpired()
      ->withCreditsLeftColumn()
      ->select('CreditsLeft')
      ->findOne();
  }

}
