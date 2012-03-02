<?php

class PropelMigration_1330633441
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
        ) ENGINE=InnoDB;

        DROP TABLE IF EXISTS `shopping_cart_collectible`;
        CREATE TABLE `shopping_cart_collectible`
        (
          `shopping_cart_id` INTEGER NOT NULL,
          `collectible_for_sale_id` INTEGER NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `price` FLOAT,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`shopping_cart_id`,`collectible_for_sale_id`),
          INDEX `shopping_cart_collectible_FI_2` (`collectible_for_sale_id`),
          CONSTRAINT `shopping_cart_collectible_FK_1`
            FOREIGN KEY (`shopping_cart_id`)
            REFERENCES `shopping_cart` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_cart_collectible_FK_2`
            FOREIGN KEY (`collectible_for_sale_id`)
            REFERENCES `collectible_for_sale` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;
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
