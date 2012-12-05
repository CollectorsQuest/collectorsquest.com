<?php

/**
 * add is_buyer_notified and is_seller_notified flags to shopping order
 */
class PropelMigration_1354637827
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    // Get items by payment status
    /* @var $shopping_orders ShoppingOrder[] */
    $shopping_orders = ShoppingOrderQuery::create()
      ->addJoin(ShoppingOrderPeer::ID, ShoppingPaymentPeer::SHOPPING_ORDER_ID, Criteria::LEFT_JOIN)
      ->add(ShoppingPaymentPeer::STATUS, 5)
      ->find();

    foreach ($shopping_orders as $shopping_order)
    {
      $shopping_order
        ->setIsBuyerNotified(true)
        ->setIsSellerNotified(true)
        ->save();
    }

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

      ALTER TABLE `shopping_order`
          ADD `is_buyer_notified` TINYINT(1) DEFAULT 0 AFTER `progress`,
          ADD `is_seller_notified` TINYINT(1) DEFAULT 0 AFTER `is_buyer_notified`;

      ALTER TABLE `shopping_order_archive`
          ADD `is_buyer_notified` TINYINT(1) DEFAULT 0 AFTER `progress`,
          ADD `is_seller_notified` TINYINT(1) DEFAULT 0 AFTER `is_buyer_notified`;

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
    return array(
      'propel' => '
      SET FOREIGN_KEY_CHECKS = 0;

      ALTER TABLE `shopping_order` DROP `is_buyer_notified`;

      ALTER TABLE `shopping_order` DROP `is_seller_notified`;

      ALTER TABLE `shopping_order_archive` DROP `is_buyer_notified`;

      ALTER TABLE `shopping_order_archive` DROP `is_seller_notified`;

      SET FOREIGN_KEY_CHECKS = 1;
',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
',
    );
  }

}