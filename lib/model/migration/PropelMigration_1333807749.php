<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1333807749.
 * Generated on 2012-04-07 10:09:09 by root
 */
class PropelMigration_1333807749
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

CREATE INDEX `private_message_FI_1` ON `private_message` (`sender`);
CREATE INDEX `private_message_FI_2` ON `private_message` (`receiver`);

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

ALTER TABLE `private_message` DROP INDEX `private_message_FI_1`;
ALTER TABLE `private_message` DROP INDEX `private_message_FI_2`;

',
);
	}
}