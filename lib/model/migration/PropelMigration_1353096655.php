<?php

/**
 * This migration will push all sold collectibles to archive
 */
class PropelMigration_1353096655
{

  public function preUp($manager)
  {
    // Get items by payment status
    /* @var $collectibles PropelObjectCollection */
    $collectibles = CollectibleQuery::create()
      ->addJoin(CollectiblePeer::ID, ShoppingOrderPeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN)
      ->addJoin(ShoppingOrderPeer::ID, ShoppingPaymentPeer::SHOPPING_ORDER_ID, Criteria::LEFT_JOIN)
      ->add(ShoppingPaymentPeer::STATUS, 5)
      ->find();

    foreach ($collectibles as $collectible)
    {
      $collectible->delete();
    }

    echo sprintf("%d - collectibles was pushed to archive\n", $collectibles->count());

    // Get items by isSold status
    /* @var $collectibles PropelObjectCollection */
    $collectibles = CollectibleQuery::create()
      ->useCollectibleForSaleQuery()
        ->filterByIsSold(true)
      ->endUse()
      ->find();

    foreach ($collectibles as $collectible)
    {
      $collectible->delete();
    }

    echo sprintf("%d - collectibles with isSold=true was pushed to archive\n", $collectibles->count());

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
    return array (
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
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
    return array (
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}