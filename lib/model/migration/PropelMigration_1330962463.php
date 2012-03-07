<?php

class PropelMigration_1330962463
{
	public function preUp($manager)
	{

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
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `shopping_order`;
        CREATE TABLE `shopping_order`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `uuid` VARCHAR(8),
          `session_id` VARCHAR(32),
          `shopping_cart_id` INTEGER NOT NULL,
          `collectible_for_sale_id` INTEGER NOT NULL,
          `collector_id` INTEGER,
          `shopping_payment_id` INTEGER,
          `shipping_country` CHAR(2),
          `note_to_seller` VARCHAR(255),
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `shopping_order_U_1` (`shopping_cart_id`, `collectible_for_sale_id`),
          INDEX `shopping_order_FI_2` (`collectible_for_sale_id`),
          INDEX `shopping_order_FI_3` (`collector_id`),
          INDEX `shopping_order_FI_4` (`shopping_payment_id`),
          INDEX `shopping_order_FI_5` (`shipping_country`),
          CONSTRAINT `shopping_order_FK_1`
            FOREIGN KEY (`shopping_cart_id`)
            REFERENCES `shopping_cart` (`id`)
            ON DELETE RESTRICT,
          CONSTRAINT `shopping_order_FK_2`
            FOREIGN KEY (`collectible_for_sale_id`)
            REFERENCES `collectible_for_sale` (`id`)
            ON DELETE RESTRICT,
          CONSTRAINT `shopping_order_FK_3`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE SET NULL,
          CONSTRAINT `shopping_order_FK_4`
            FOREIGN KEY (`shopping_payment_id`)
            REFERENCES `shopping_payment` (`id`)
            ON DELETE SET NULL,
          CONSTRAINT `shopping_order_FK_5`
            FOREIGN KEY (`shipping_country`)
            REFERENCES `geo_country` (`iso3166`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        ALTER TABLE `shopping_order` AUTO_INCREMENT = 1000001;

        DROP TABLE IF EXISTS `shopping_payment`;
        CREATE TABLE `shopping_payment`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `shopping_order_id` INTEGER NOT NULL,
          `session_id` VARCHAR(32),
          `processor` TINYINT DEFAULT 0 NOT NULL,
          `status` TINYINT DEFAULT 0 NOT NULL,
          `currency` VARCHAR(3),
          `amount_total` FLOAT,
          `amount_collectibles` FLOAT,
          `amount_shipping` FLOAT,
          `amount_tax` FLOAT,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `shopping_payment_FI_1` (`shopping_order_id`),
          CONSTRAINT `shopping_payment_FK_1`
            FOREIGN KEY (`shopping_order_id`)
            REFERENCES `shopping_order` (`id`)
            ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        DROP TABLE IF EXISTS `shopping_payment_extra_property`;
        CREATE TABLE `shopping_payment_extra_property`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `property_name` VARCHAR(255) NOT NULL,
          `property_value` TEXT,
          `shopping_payment_id` INTEGER NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `shopping_payment_extra_property_FI_1` (`shopping_payment_id`),
          CONSTRAINT `shopping_payment_extra_property_FK_1`
            FOREIGN KEY (`shopping_payment_id`)
            REFERENCES `shopping_payment` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
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
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `shopping_order`;
        DROP TABLE IF EXISTS `shopping_payment`;
        DROP TABLE IF EXISTS `shopping_payment_extra_property`;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
    );
	}

}
