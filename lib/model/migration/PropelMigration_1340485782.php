<?php

/**
 * Update shippinc_cart_collectible:
 *   -  shipping_country_iso3166 becomes NOT NULL
 *   -  shipping_fee_amount becomes NULL
 *   -  shipping_type propel enum column is added, which mirrors shipping_reference.shipping_Type
 *
 * Generated on 2012-06-24 37:09:42 by root
 */
class PropelMigration_1340485782
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

ALTER TABLE `shopping_cart_collectible` CHANGE `shipping_country_iso3166` `shipping_country_iso3166` CHAR(2) NOT NULL;

ALTER TABLE `shopping_cart_collectible` CHANGE `shipping_fee_amount` `shipping_fee_amount` INTEGER;

ALTER TABLE `shopping_cart_collectible` ADD `shipping_type` TINYINT NOT NULL AFTER `shipping_fee_amount`;

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

ALTER TABLE `shopping_cart_collectible` CHANGE `shipping_country_iso3166` `shipping_country_iso3166` CHAR(2);

ALTER TABLE `shopping_cart_collectible` CHANGE `shipping_fee_amount` `shipping_fee_amount` INTEGER DEFAULT 0 NOT NULL;

ALTER TABLE `shopping_cart_collectible` DROP `shipping_type`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}