<?php

/**
 * Remove foreign Reference from ShoppingOrder collectible_id field, fix ShoppingOrder table to match it schema.yml
 */
class PropelMigration_1353096650
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

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_1`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_2`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_3`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_4`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_5`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_6`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_7`;

        DROP INDEX `shopping_order_FI_1` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_3` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_4` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_5` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_6` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_7` ON `shopping_order`;

        ALTER TABLE `shopping_order` CHANGE `seller_id` `seller_id` INTEGER;

        CREATE INDEX `shopping_order_FI_1` ON `shopping_order` (`seller_id`);
        CREATE INDEX `shopping_order_FI_2` ON `shopping_order` (`collector_id`);
        CREATE INDEX `shopping_order_FI_4` ON `shopping_order` (`shopping_payment_id`);
        CREATE INDEX `shopping_order_FI_5` ON `shopping_order` (`shipping_address_id`);
        CREATE INDEX `shopping_order_FI_6` ON `shopping_order` (`shipping_country_iso3166`);

        CREATE INDEX `shopping_order_I_1` ON `shopping_order` (`collectible_id`);
        CREATE INDEX `shopping_order_I_2` ON `shopping_order` (`buyer_email`);


        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_1`
            FOREIGN KEY (`seller_id`)
            REFERENCES `collector` (`id`)
            ON DELETE SET NULL;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_2`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE SET NULL;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_3`
            FOREIGN KEY (`shopping_cart_id`)
            REFERENCES `shopping_cart` (`id`)
            ON DELETE RESTRICT;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_4`
            FOREIGN KEY (`shopping_payment_id`)
            REFERENCES `shopping_payment` (`id`)
            ON DELETE SET NULL;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_5`
            FOREIGN KEY (`shipping_address_id`)
            REFERENCES `collector_address` (`id`)
            ON DELETE SET NULL;

        ALTER TABLE `shopping_order` ADD CONSTRAINT `shopping_order_FK_6`
            FOREIGN KEY (`shipping_country_iso3166`)
            REFERENCES `geo_country` (`iso3166`);

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

        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_1`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_2`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_3`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_4`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_5`;
        ALTER TABLE `shopping_order` DROP FOREIGN KEY `shopping_order_FK_6`;

        DROP INDEX `shopping_order_FI_1` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_2` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_4` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_5` ON `shopping_order`;
        DROP INDEX `shopping_order_FI_6` ON `shopping_order`;
        DROP INDEX `shopping_order_I_1` ON `shopping_order`;
        DROP INDEX `shopping_order_I_2` ON `shopping_order`;

        ALTER TABLE `shopping_order` CHANGE `seller_id` `seller_id` INTEGER NOT NULL;

        CREATE INDEX `shopping_order_FI_1` ON `shopping_order` (`collector_id`);
        CREATE INDEX `shopping_order_FI_3` ON `shopping_order` (`collectible_id`);
        CREATE INDEX `shopping_order_FI_4` ON `shopping_order` (`shopping_payment_id`);
        CREATE INDEX `shopping_order_FI_5` ON `shopping_order` (`shipping_country_iso3166`);
        CREATE INDEX `shopping_order_FI_6` ON `shopping_order` (`seller_id`);
        CREATE INDEX `shopping_order_FI_7` ON `shopping_order` (`shipping_address_id`);

        ALTER TABLE `shopping_order`
          ADD CONSTRAINT `shopping_order_FK_1`
          FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE SET NULL,

          ADD CONSTRAINT `shopping_order_FK_2`
          FOREIGN KEY (`shopping_cart_id`) REFERENCES `shopping_cart` (`id`),

          ADD CONSTRAINT `shopping_order_FK_3`
          FOREIGN KEY (`collectible_id`) REFERENCES `collectible` (`id`),

          ADD CONSTRAINT `shopping_order_FK_4`
          FOREIGN KEY (`shopping_payment_id`) REFERENCES `shopping_payment` (`id`) ON DELETE SET NULL,

          ADD CONSTRAINT `shopping_order_FK_5`
          FOREIGN KEY (`shipping_country_iso3166`) REFERENCES `geo_country` (`iso3166`),

          ADD CONSTRAINT `shopping_order_FK_6`
          FOREIGN KEY (`seller_id`) REFERENCES `collector` (`id`),

          ADD CONSTRAINT `shopping_order_FK_7`
          FOREIGN KEY (`shipping_address_id`) REFERENCES `collector_address` (`id`) ON DELETE SET NULL;

        SET FOREIGN_KEY_CHECKS = 1;

',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
',
    );
  }

}