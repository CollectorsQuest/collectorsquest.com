<?php

class PropelMigration_1338844116
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

        ALTER TABLE `tag` CHANGE `name` `name` VARBINARY(128)  NULL  DEFAULT NULL;
        ALTER TABLE `tag` DROP INDEX `name`;
        ALTER TABLE `tag` ADD UNIQUE INDEX (`name`);

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

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

        ALTER TABLE `tag` CHANGE `name` `name` VARCHAR(128)  NULL  DEFAULT NULL;
        ALTER TABLE `tag` DROP INDEX `name`;
        ALTER TABLE `tag` ADD INDEX (`name`);

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
	}

}
