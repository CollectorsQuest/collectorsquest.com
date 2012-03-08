<?php

class PropelMigration_1330899669
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

        DROP TABLE IF EXISTS `shopping_cart`;
        CREATE TABLE `shopping_cart`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `collector_id` INTEGER,
          `session_id` VARCHAR(32),
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `shopping_cart_U_1` (`collector_id`),
          UNIQUE INDEX `shopping_cart_U_2` (`collector_id`, `session_id`),
          CONSTRAINT `shopping_cart_FK_1`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        DROP TABLE IF EXISTS `shopping_cart_collectible`;
        CREATE TABLE `shopping_cart_collectible`
        (
          `shopping_cart_id` INTEGER NOT NULL,
          `collectible_id` INTEGER NOT NULL,
          `price_amount` INTEGER DEFAULT 0 NOT NULL,
          `price_currency` CHAR(3) DEFAULT 'USD' NOT NULL,
          `tax_amount` INTEGER DEFAULT 0 NOT NULL,
          `shipping_country_iso3166` CHAR(2),
          `shipping_fee_amount` INTEGER DEFAULT 0 NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`shopping_cart_id`,`collectible_id`),
          INDEX `shopping_cart_collectible_FI_2` (`collectible_id`),
          INDEX `shopping_cart_collectible_FI_3` (`shipping_country_iso3166`),
          CONSTRAINT `shopping_cart_collectible_FK_1`
            FOREIGN KEY (`shopping_cart_id`)
            REFERENCES `shopping_cart` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_cart_collectible_FK_2`
            FOREIGN KEY (`collectible_id`)
            REFERENCES `collectible` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_cart_collectible_FK_3`
            FOREIGN KEY (`shipping_country_iso3166`)
            REFERENCES `geo_country` (`iso3166`)
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
        DROP TABLE IF EXISTS `shopping_cart_collectible`;
        DROP TABLE IF EXISTS `shopping_cart`;
      ",
    );
	}

}
