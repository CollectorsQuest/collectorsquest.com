<?php

/**
 * Add extra tables and schema changes for grouping of items in the cart
 */
class PropelMigration_1353494211
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
    return array (
      'propel' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_5`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_6`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_7`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_2`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_3`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_4`;

        DROP INDEX `shopping_order_U_1` ON `shopping_order`;

        DROP INDEX `shopping_order_FI_5` ON `shopping_order`;

        DROP INDEX `shopping_order_FI_6` ON `shopping_order`;

        DROP INDEX `shopping_order_FI_3` ON `shopping_order`;

        ALTER TABLE `shopping_order` ADD
        (
          `group_key` VARCHAR(128)
        );

        ALTER TABLE `shopping_order` DROP `seller_id`;

        ALTER TABLE `shopping_order` DROP `collectible_id`;

        ALTER TABLE `shopping_order` DROP `shipping_carrier`;

        ALTER TABLE `shopping_order` DROP `shipping_tracking_number`;

        CREATE INDEX `shopping_order_FI_3` ON `shopping_order` (`shopping_cart_id`);

        CREATE INDEX `shopping_order_I_1` ON `shopping_order` (`buyer_email`);

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_2`
          FOREIGN KEY (`shopping_payment_id`)
          REFERENCES `shopping_payment` (`id`)
          ON DELETE SET NULL;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_3`
          FOREIGN KEY (`shopping_cart_id`)
          REFERENCES `shopping_cart` (`id`)
          ON DELETE RESTRICT;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_4`
          FOREIGN KEY (`shipping_address_id`)
          REFERENCES `collector_address` (`id`)
          ON DELETE SET NULL;

        ALTER TABLE `shopping_payment` DROP FOREIGN KEY `shopping_payment_FK_1`;

        ALTER TABLE `shopping_payment` ADD CONSTRAINT `shopping_payment_FK_1`
          FOREIGN KEY (`shopping_order_id`)
          REFERENCES `shopping_order` (`id`)
          ON DELETE RESTRICT;



        CREATE TABLE `shopping_order_collectible`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `shopping_order_id` INTEGER NOT NULL,
          `seller_id` INTEGER,
          `collectible_id` INTEGER NOT NULL,
          `shipping_carrier` TINYINT,
          `shipping_tracking_number` VARCHAR(64),
          `price_amount` INTEGER DEFAULT 0 NOT NULL,
          `price_currency` CHAR(3) DEFAULT \'USD\' NOT NULL,
          `tax_amount` INTEGER DEFAULT 0 NOT NULL,
          `shipping_fee_amount` INTEGER,
          `shipping_type` TINYINT,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `shopping_order_collectible_U_1` (`collectible_id`),
          INDEX `shopping_order_collectible_FI_1` (`shopping_order_id`),
          INDEX `shopping_order_collectible_FI_2` (`seller_id`),
          CONSTRAINT `shopping_order_collectible_FK_1`
            FOREIGN KEY (`shopping_order_id`)
            REFERENCES `shopping_order` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_order_collectible_FK_2`
            FOREIGN KEY (`seller_id`)
            REFERENCES `collector` (`id`)
            ON DELETE SET NULL,
          CONSTRAINT `shopping_order_collectible_FK_3`
            FOREIGN KEY (`collectible_id`)
            REFERENCES `collectible` (`id`)
            ON DELETE RESTRICT
        ) ENGINE=InnoDB;

        # This restores the fkey checks, after having unset them earlier
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

        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `shopping_order_collectible`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_2`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_3`;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_4`;

        DROP INDEX `shopping_order_I_1` ON `shopping_order`;

        DROP INDEX `shopping_order_FI_3` ON `shopping_order`;

        ALTER TABLE `shopping_order` ADD
        (
          `seller_id` INTEGER NOT NULL,
          `collectible_id` INTEGER NOT NULL,
          `shipping_carrier` TINYINT,
          `shipping_tracking_number` VARCHAR(64)
        );

        ALTER TABLE `shopping_order` DROP `group_key`;

        CREATE INDEX `shopping_order_FI_3` ON `shopping_order` (`collectible_id`);

        CREATE UNIQUE INDEX `shopping_order_U_1` ON `shopping_order` (`shopping_cart_id`,`collectible_id`);

        CREATE INDEX `shopping_order_FI_6` ON `shopping_order` (`seller_id`);

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_2`
          FOREIGN KEY (`shopping_cart_id`)
          REFERENCES `shopping_cart` (`id`);

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_3`
          FOREIGN KEY (`collectible_id`)
          REFERENCES `collectible` (`id`);

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_4`
          FOREIGN KEY (`shopping_payment_id`)
          REFERENCES `shopping_payment` (`id`)
          ON DELETE SET NULL;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_6`
          FOREIGN KEY (`seller_id`)
          REFERENCES `collector` (`id`);

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_7`
          FOREIGN KEY (`shipping_address_id`)
          REFERENCES `collector_address` (`id`)
          ON DELETE SET NULL;

        ALTER TABLE `shopping_payment` DROP FOREIGN KEY `shopping_payment_FK_1`;

        ALTER TABLE `shopping_payment` ADD CONSTRAINT `shopping_payment_FK_1`
          FOREIGN KEY (`shopping_order_id`)
          REFERENCES `shopping_order` (`id`);
        SET FOREIGN_KEY_CHECKS = 1;

      ',
    );
  }

}
