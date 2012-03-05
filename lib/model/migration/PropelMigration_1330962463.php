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
        DROP TABLE IF EXISTS `shopping_order`;

        CREATE TABLE `shopping_order`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `uuid` VARCHAR(8),
          `session_id` VARCHAR(32),
          `shopping_cart_id` INTEGER NOT NULL,
          `collectible_for_sale_id` INTEGER NOT NULL,
          `collector_id` INTEGER,
          `note_to_seller` VARCHAR(255),
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `shopping_order_FI_1` (`shopping_cart_id`),
          INDEX `shopping_order_FI_2` (`collectible_for_sale_id`),
          INDEX `shopping_order_FI_3` (`collector_id`),
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
            ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        ALTER TABLE `shopping_order` AUTO_INCREMENT = 1000001;
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
        DROP TABLE IF EXISTS `shopping_order`;
      ",
    );
	}

}
