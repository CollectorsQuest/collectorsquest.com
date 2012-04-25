<?php

class PropelMigration_1335355431
{

  public function preUp($manager)
  {
    // add the pre-migration code here
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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;

        TRUNCATE TABLE `shopping_payment_extra_property`;
        TRUNCATE TABLE `shopping_payment`;
        TRUNCATE TABLE `shopping_order`;
        TRUNCATE TABLE `shopping_cart_collectible`;
        TRUNCATE TABLE `shopping_cart`;

        ALTER TABLE `shopping_order` ADD `seller_id` INT NOT NULL AFTER `uuid`;
        ALTER TABLE `shopping_order` ADD INDEX `shopping_order_FI_6` (`seller_id`);
        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_6`
          FOREIGN KEY (`seller_id`)
          REFERENCES `collector` (`id`)
          ON DELETE RESTRICT;

        ALTER TABLE `shopping_order` ADD `shipping_address_id` INT NULL DEFAULT NULL AFTER `buyer_email`;
        ALTER TABLE `shopping_order` ADD INDEX `shopping_order_FI_7` (`shipping_address_id`);
        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_7`
          FOREIGN KEY (`shipping_address_id`)
          REFERENCES `collector_address` (`id`)
          ON DELETE SET NULL;

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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_6`;
        ALTER TABLE `shopping_order` DROP `seller_id`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_7`;
        ALTER TABLE `shopping_order` DROP `shipping_address_id`;

        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
