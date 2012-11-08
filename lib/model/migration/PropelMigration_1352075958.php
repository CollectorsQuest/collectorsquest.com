<?php

/**
 * Migration to automatic set rating & machine tags for collectibles from predefined collections
 */
class PropelMigration_1352075958
{

  public function preUp()
  {
    $q = CollectibleForSaleQuery::create()
      ->filterByIsReady(true)
      ->filterByPriceAmount(1, Criteria::GREATER_EQUAL)
      ->filterByQuantity(1, Criteria::GREATER_EQUAL)
      ->isPartOfCollection();

    /** @var $collectibles_for_sale CollectibleForSale[] */
    $collectibles_for_sale = $q->find();

    $collectors = array();
    foreach ($collectibles_for_sale as $collectible_for_sale)
    {
      if (!$collectible_for_sale->hasActiveCredit())
      {
        $collector = $collectible_for_sale->getCollector();

        $collectible_for_sale->setIsReady(false);
        $collectible_for_sale->setUpdatedAt($collectible_for_sale->getUpdatedAt());
        $collectible_for_sale->save();

        if (!isset($collectors[$collector->getId()]))
        {
          $collectors[$collector->getId()] = array(
            'name' => $collector->getDisplayName(), 'items' => 1
          );
        }
        else
        {
          $collectors[$collector->getId()]['items']++;
        }
      }
    }

    foreach ($collectors as $id => $data)
    {
      $q = PackageTransactionQuery::create()
        ->filterByCollectorId($id)
        ->paidFor()
        ->hasUnusedCredits();

      if (!$q->findOne())
      {
        $transaction = new PackageTransaction();
        $transaction->setPackageId(PackagePeer::PACKAGE_ID_ADMIN);
        $transaction->setCollectorId($id);
        $transaction->setCredits($data['items'] < 100 ? 100 : $data['items']);
        $transaction->setExpiryDate(strtotime('+1 year'));
        $transaction->confirmPayment(); // includes save()
      }
    }

    return true;
  }

  public function postUp()
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
