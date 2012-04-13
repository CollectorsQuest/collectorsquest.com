<?php

class PropelMigration_1334140756
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp()
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
    return array('propel' => '
      # This is a fix for InnoDB in MySQL >= 4.1.x
      # It "suspends judgement" for fkey relationships until are tables are set.
      SET FOREIGN_KEY_CHECKS = 0;

      ALTER TABLE `shopping_order` ADD `buyer_email` VARCHAR(128) AFTER `shopping_payment_id`;
      ALTER TABLE `shopping_order` ADD `shipping_full_name` VARCHAR(255) NOT NULL AFTER `buyer_email`;
      ALTER TABLE `shopping_order` ADD `shipping_phone` VARCHAR(50) AFTER `shipping_full_name`;
      ALTER TABLE `shopping_order` ADD `shipping_address_line_1` VARCHAR(255) NOT NULL AFTER `shipping_phone`;
      ALTER TABLE `shopping_order` ADD `shipping_address_line_2` VARCHAR(255) AFTER `shipping_address_line_1`;
      ALTER TABLE `shopping_order` ADD `shipping_city` VARCHAR(100) NOT NULL AFTER `shipping_address_line_2`;
      ALTER TABLE `shopping_order` ADD `shipping_state_region` VARCHAR(100) AFTER `shipping_city`;
      ALTER TABLE `shopping_order` ADD `shipping_zip_postcode` VARCHAR(50) NOT NULL AFTER `shipping_state_region`;

      SET FOREIGN_KEY_CHECKS = 1;
    ');
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
    return array('propel' => '
      # This is a fix for InnoDB in MySQL >= 4.1.x
      # It "suspends judgement" for fkey relationships until are tables are set.
      SET FOREIGN_KEY_CHECKS = 0;

      ALTER TABLE `shopping_order` DROP `buyer_email`;
      ALTER TABLE `shopping_order` DROP `shipping_full_name`;
      ALTER TABLE `shopping_order` DROP `shipping_phone`;
      ALTER TABLE `shopping_order` DROP `shipping_address_line_1`;
      ALTER TABLE `shopping_order` DROP `shipping_address_line_2`;
      ALTER TABLE `shopping_order` DROP `shipping_city`;
      ALTER TABLE `shopping_order` DROP `shipping_state_region`;
      ALTER TABLE `shopping_order` DROP `shipping_zip_postcode`;

      SET FOREIGN_KEY_CHECKS = 1;
    ');
	}

}
