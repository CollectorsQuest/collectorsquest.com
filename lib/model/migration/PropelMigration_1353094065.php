<?php

/**
 * Remove comments pointing to deleted objects
 */
class PropelMigration_1353094065
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp()
  {
    /* @var $shopping_orders ShoppingOrder[] */
    $shopping_orders = ShoppingOrderQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();

    foreach ($shopping_orders as $shopping_order)
    {
      if ($payment = $shopping_order->getShoppingPayment())
      {
        switch ($payment->getStatus())
        {
          case ShoppingPaymentPeer::STATUS_INITIALIZED:
          case ShoppingPaymentPeer::STATUS_INPROGRESS:
          case ShoppingPaymentPeer::STATUS_CANCELLED:
          case ShoppingPaymentPeer::STATUS_FAILED:
            $shopping_order->setProgress(ShoppingOrderPeer::PROGRESS_STEP2);
            $shopping_order->save();
            break;
          case ShoppingPaymentPeer::STATUS_CONFIRMED:
          case ShoppingPaymentPeer::STATUS_COMPLETED:
            $shopping_order->setProgress(ShoppingOrderPeer::PROGRESS_STEP3);
            $shopping_order->save();
            break;
        }
      }
      else
      {
        $shopping_order->setProgress(ShoppingOrderPeer::PROGRESS_STEP1);
        $shopping_order->save();
      }
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
    return array (
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `shopping_order` ADD `progress` TINYINT NOT NULL DEFAULT 0 AFTER `note_to_seller`;
        ALTER TABLE `shopping_order_archive` ADD `progress` TINYINT NOT NULL DEFAULT 0 AFTER `note_to_seller`;

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

        ALTER TABLE `shopping_order` DROP `progress`;
        ALTER TABLE `shopping_order_archive` DROP `progress`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}
