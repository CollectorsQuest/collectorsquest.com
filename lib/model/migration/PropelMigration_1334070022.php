<?php

class PropelMigration_1334070022
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

      ALTER TABLE `shopping_cart` CHANGE `session_id` `cookie_uuid` VARCHAR(32)  NULL  DEFAULT NULL;
      ALTER TABLE `shopping_payment` CHANGE `session_id` `cookie_uuid` VARCHAR(32)  NULL  DEFAULT NULL;

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

      ALTER TABLE `shopping_cart` CHANGE `cookie_uuid` `session_id` VARCHAR(32)  NULL  DEFAULT NULL;
      ALTER TABLE `shopping_payment` CHANGE `cookie_uuid` `session_id` VARCHAR(32)  NULL  DEFAULT NULL;

      SET FOREIGN_KEY_CHECKS = 1;
    ');
	}

}
