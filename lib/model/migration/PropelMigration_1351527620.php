<?php

/**
 * Migration to add new shopping_order shopping_payment archive tables
 */
class PropelMigration_1351527620
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
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_4`;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_4`
          FOREIGN KEY (`collectible_id`)
          REFERENCES `collectible` (`id`)
          ON DELETE CASCADE;

        ALTER TABLE `shopping_payment` DROP FOREIGN KEY `shopping_payment_FK_1`;

        ALTER TABLE `shopping_payment` ADD CONSTRAINT `shopping_payment_FK_1`
          FOREIGN KEY (`shopping_order_id`)
          REFERENCES `shopping_order` (`id`)
          ON DELETE CASCADE;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'archive' => "
        SET FOREIGN_KEY_CHECKS = 0;

        CREATE TABLE `shopping_order_archive`
        (
          `id` INTEGER NOT NULL,
          `uuid` VARCHAR(8),
          `seller_id` INTEGER,
          `collector_id` INTEGER,
          `shopping_cart_id` INTEGER,
          `collectible_id` INTEGER,
          `shopping_payment_id` INTEGER,
          `buyer_email` VARCHAR(128),
          `shipping_address_id` INTEGER,
          `shipping_full_name` VARCHAR(255) NOT NULL,
          `shipping_phone` VARCHAR(50),
          `shipping_address_line_1` VARCHAR(255) NOT NULL,
          `shipping_address_line_2` VARCHAR(255),
          `shipping_city` VARCHAR(100) NOT NULL,
          `shipping_state_region` VARCHAR(100),
          `shipping_zip_postcode` VARCHAR(50) NOT NULL,
          `shipping_country_iso3166` CHAR(2),
          `shipping_carrier` TINYINT,
          `shipping_tracking_number` VARCHAR(64),
          `note_to_seller` VARCHAR(255),
          `updated_at` DATETIME,
          `created_at` DATETIME,
          `archived_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `shopping_order_archive_I_1` (`seller_id`),
          INDEX `shopping_order_archive_I_2` (`collector_id`),
          INDEX `shopping_order_archive_I_3` (`shopping_cart_id`),
          INDEX `shopping_order_archive_I_4` (`collectible_id`),
          INDEX `shopping_order_archive_I_5` (`shopping_payment_id`),
          INDEX `shopping_order_archive_I_6` (`buyer_email`),
          INDEX `shopping_order_archive_I_7` (`shipping_address_id`)
        ) ENGINE=InnoDB;

        CREATE TABLE `shopping_payment_archive`
        (
          `id` INTEGER NOT NULL,
          `shopping_order_id` INTEGER,
          `cookie_uuid` VARCHAR(32),
          `processor` TINYINT DEFAULT 0 NOT NULL,
          `status` TINYINT DEFAULT 0 NOT NULL,
          `currency` CHAR(3) DEFAULT 'USD',
          `amount_total` INTEGER DEFAULT 0 NOT NULL,
          `amount_collectibles` INTEGER DEFAULT 0 NOT NULL,
          `amount_shipping_fee` INTEGER DEFAULT 0 NOT NULL,
          `amount_tax` INTEGER DEFAULT 0 NOT NULL,
          `updated_at` DATETIME,
          `created_at` DATETIME,
          `archived_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `shopping_payment_archive_I_1` (`shopping_order_id`)
        ) ENGINE=InnoDB;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
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

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_4`;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_4`
        FOREIGN KEY (`shopping_payment_id`)
        REFERENCES `shopping_payment` (`id`)
        ON DELETE SET NULL;

        ALTER TABLE `shopping_payment` DROP FOREIGN KEY `shopping_payment_FK_1`;

        ALTER TABLE `shopping_payment` ADD CONSTRAINT `shopping_payment_FK_1`
          FOREIGN KEY (`shopping_order_id`)
          REFERENCES `shopping_order` (`id`);

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'archive' => '
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `shopping_order_archive`;
        DROP TABLE IF EXISTS `shopping_payment_archive`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }
}
