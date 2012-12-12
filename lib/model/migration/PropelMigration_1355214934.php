<?php

/**
 * Migration to add seller promo codes
 */
class PropelMigration_1355214934
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

        ALTER TABLE `shopping_cart_collectible`
            ADD `seller_promotion_id` INTEGER AFTER `collectible_id`,
            ADD `promotion_amount` INTEGER DEFAULT 0 AFTER `price_currency`;

        CREATE INDEX `shopping_cart_collectible_I_1` ON `shopping_cart_collectible` (`seller_promotion_id`);

        ALTER TABLE `shopping_payment`
            ADD `seller_promotion_id` INTEGER AFTER `amount_tax`,
            ADD `amount_promotion` INTEGER DEFAULT 0 AFTER `seller_promotion_id`;

        CREATE INDEX `shopping_payment_I_1` ON `shopping_payment` (`seller_promotion_id`);


        CREATE TABLE `seller_promotion`
        (
            `id` INTEGER NOT NULL AUTO_INCREMENT,
            `seller_id` INTEGER NOT NULL,
            `collector_id` INTEGER,
            `collectible_id` INTEGER,
            `promotion_code` VARCHAR(100) NOT NULL,
            `promotion_name` VARCHAR(255) NOT NULL,
            `promotion_desc` TEXT,
            `amount` INTEGER DEFAULT 0,
            `amount_type` TINYINT NOT NULL,
            `quantity` INTEGER DEFAULT 0,
            `expiry_date` DATETIME,
            `is_expired` TINYINT(1) DEFAULT 0,
            `created_at` DATETIME,
            `updated_at` DATETIME,
            PRIMARY KEY (`id`),
            INDEX `seller_promotion_I_1` (`promotion_code`),
            INDEX `seller_promotion_FI_1` (`seller_id`),
            INDEX `seller_promotion_FI_2` (`collector_id`),
            INDEX `seller_promotion_FI_3` (`collectible_id`),
            CONSTRAINT `seller_promotion_FK_1`
                FOREIGN KEY (`seller_id`)
                REFERENCES `collector` (`id`)
                ON DELETE CASCADE,
            CONSTRAINT `seller_promotion_FK_2`
                FOREIGN KEY (`collector_id`)
                REFERENCES `collector` (`id`)
                ON DELETE CASCADE,
            CONSTRAINT `seller_promotion_FK_3`
                FOREIGN KEY (`collectible_id`)
                REFERENCES `collectible` (`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB;

        SET FOREIGN_KEY_CHECKS = 1;
',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
',
      'archive' => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `shopping_payment_archive`
            ADD `seller_promotion_id` INTEGER AFTER `amount_tax`,
            ADD `amount_promotion` INTEGER DEFAULT 0 AFTER `seller_promotion_id`;

        CREATE INDEX `shopping_payment_archive_I_1` ON `shopping_payment_archive` (`seller_promotion_id`);

        CREATE TABLE `seller_promotion_archive`
        (
            `id` INTEGER NOT NULL,
            `seller_id` INTEGER,
            `collector_id` INTEGER,
            `collectible_id` INTEGER,
            `promotion_code` VARCHAR(100) NOT NULL,
            `promotion_name` VARCHAR(255) NOT NULL,
            `promotion_desc` TEXT,
            `amount` INTEGER DEFAULT 0,
            `amount_type` TINYINT NOT NULL,
            `quantity` INTEGER DEFAULT 0,
            `expiry_date` DATETIME,
            `is_expired` TINYINT(1) DEFAULT 0,
            `updated_at` DATETIME,
            `created_at` DATETIME,
            `archived_at` DATETIME,
            PRIMARY KEY (`id`),
            INDEX `seller_promotion_archive_I_1` (`seller_id`),
            INDEX `seller_promotion_archive_I_2` (`collector_id`),
            INDEX `seller_promotion_archive_I_3` (`collectible_id`),
            INDEX `seller_promotion_archive_I_4` (`promotion_code`)
        ) ENGINE=InnoDB;

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

        DROP TABLE IF EXISTS `seller_promotion`;

        DROP INDEX `shopping_cart_collectible_I_1` ON `shopping_cart_collectible`;

        ALTER TABLE `shopping_cart_collectible` DROP `seller_promotion_id`;

        ALTER TABLE `shopping_cart_collectible` DROP `promotion_amount`;

        DROP INDEX `shopping_payment_I_1` ON `shopping_payment`;

        ALTER TABLE `shopping_payment` DROP `seller_promotion_id`;

        ALTER TABLE `shopping_payment` DROP `amount_promotion`;

        SET FOREIGN_KEY_CHECKS = 1;
',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
',
      'archive' => '
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `seller_promotion_archive`;

        DROP INDEX `shopping_payment_archive_I_1` ON `shopping_payment_archive`;

        ALTER TABLE `shopping_payment_archive` DROP `seller_promotion_id`;

        ALTER TABLE `shopping_payment_archive` DROP `amount_promotion`;

        SET FOREIGN_KEY_CHECKS = 1;
',
    );
  }

}