<?php

class PropelMigration_1342037378
{

  public function preUp($manager)
  {
    // WARNING! THIS MIGRATION WILL DO THE FOLLOWING:

    // If there are collectibles for sale for a particualr collector that do not
    // have a credit assigned to them, they will get credits asigned even if the
    // collector has insufficient credits - a new PackageTransaction
    // will be created, with the necessary number of credits.

    // CollectiblesForSale without shipping details set
    // WILL BE TREATED AS FREE SHIPPING,
    // INCLUDING FOR INTERNATIONAL BUYERS!

    $collector_ids = array(
      '10287','10282','12','6'
    );

    $package = PackageQuery::create()->findPk(1);

    foreach ($collector_ids as $collector_id)
    {
      $collector = CollectorQuery::create()->findPk($collector_id);
      if (!$collector) continue;

      $for_sale_without_credits = CollectibleForSaleQuery::create()
        ->joinWith('CollectibleForSale.Collectible')
        ->filterByCollector($collector)
        ->isForSale()
        ->filterByCollectibleId(
          CollectibleForSaleQuery::create()
            ->filterByCollector($collector)
            ->isForSale()
            ->hasActiveCredit()
            ->select('CollectibleId')
            ->find(),
          Criteria::NOT_IN)
        ->find();


      echo "\n";
      echo sprintf("The collector %s:%d has the following collectilbles for sale without credits: \n", $collector->getDisplayName(), $collector->getId());

      foreach ($for_sale_without_credits as $collectible_for_sale)
      {
        echo $collectible_for_sale->getName() . "\n";
      }
      echo sprintf("Total: %d\n", count($for_sale_without_credits));

      /** @var $seller Seller */
      $seller = $collector->getSeller();

      if ($seller && count($for_sale_without_credits) > $seller->getCreditsLeft())
      {
        $creditsNeeded = count($for_sale_without_credits) - $collector->getSeller()->getCreditsLeft();

        echo sprintf("\nThe seller has insuficient credits, creating %d new credits. \n", $creditsNeeded);

        $package_trans = new PackageTransaction();
        $package_trans->setPackage($package);
        $package_trans->setCollector($collector);
        $package_trans->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID);
        $package_trans->setExpiryDate(strtotime('+1 year'));
        $package_trans->setCredits($creditsNeeded);
        $package_trans->save();
      }

      echo 'Asigning credits to collectibles... ';

      foreach ($for_sale_without_credits as $collectible_for_sale)
      {
        PackageTransactionCreditPeer::findActiveOrCreateForCollectible(
          $collectible_for_sale->getCollectible()
        ) ;
      }

      echo "complete! \n";
    }

  }

  public function postUp($manager)
  {
    // add the post-migration code here
  }

  public function preDown($manager)
  {
    // add the pre-migration code here
  }

  public function postDown($manager)
  {
    // add the post-migration code here
  }

  /**
   * Get the SQL statements for the Up migration
   *
   * @return array list of the SQL strings to execute for the Up migration
   *               the keys being the datasources
   */
  public function getUpSQL()
  {
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
