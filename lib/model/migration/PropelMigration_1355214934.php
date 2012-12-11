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

        ALTER TABLE `shopping_payment`
            ADD `seller_promotion_id` INTEGER AFTER `amount_tax`,
            ADD `amount_promotion` INTEGER DEFAULT 0 AFTER `seller_promotion_id`;

        CREATE INDEX `shopping_payment_FI_2` ON `shopping_payment` (`seller_promotion_id`);

        ALTER TABLE `shopping_payment` ADD CONSTRAINT `shopping_payment_FK_2`
            FOREIGN KEY (`seller_promotion_id`)
            REFERENCES `seller_promotion` (`id`)
            ON DELETE SET NULL;

        CREATE TABLE `seller_promotion`
        (
            `id` INTEGER NOT NULL AUTO_INCREMENT,
            `seller_id` INTEGER NOT NULL,
            `collector_id` INTEGER,
            `collectible_id` INTEGER,
            `promotion_code` VARCHAR(255) NOT NULL,
            `promotion_name` VARCHAR(255) NOT NULL,
            `promotion_desc` TEXT,
            `amount` INTEGER DEFAULT 0 NOT NULL,
            `amount_type` ENUM(\'Fixed\',\'Percentage\',\'Free Shipping\') DEFAULT \'Fixed\' NOT NULL,
            `quantity` INTEGER DEFAULT 0,
            `expiry_date` DATETIME,
            `created_at` DATETIME,
            `updated_at` DATETIME,
            PRIMARY KEY (`id`),
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

        ALTER TABLE `shopping_cart_collectible` DROP `seller_promotion_id`;

        ALTER TABLE `shopping_cart_collectible` DROP `promotion_amount`;

        ALTER TABLE `shopping_payment` DROP FOREIGN KEY `shopping_payment_FK_2`;

        DROP INDEX `shopping_payment_FI_2` ON `shopping_payment`;

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
        SET FOREIGN_KEY_CHECKS = 1;
',
    );
  }

}