<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1331114751.
 * Generated on 2012-03-07 12:05:51 by root
 */
class PropelMigration_1331114751
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

        DROP TABLE IF EXISTS `collector_remember_key`;
        CREATE TABLE `collector_remember_key`
        (
          `ip_address` CHAR(15) NOT NULL,
          `collector_id` INTEGER NOT NULL,
          `remember_key` CHAR(32),
          `created_at` DATETIME,
          PRIMARY KEY (`ip_address`),
          INDEX `collector_remember_key_FI_1` (`collector_id`),
          CONSTRAINT `collector_remember_key_FK_1`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
        DROP TABLE IF EXISTS `collector_remember_key`;
      ',
    );
	}

}
