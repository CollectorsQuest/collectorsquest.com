<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1348503327.
 * Generated on 2012-09-24 12:15:27 by root
 */
class PropelMigration_1348503327
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

CREATE TABLE `collector_rating`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`from_collector_id` INTEGER,
	`to_collector_id` INTEGER,
	`collectible_id` INTEGER NOT NULL,
	`rate` TINYINT NOT NULL,
	`rate_for` TINYINT NOT NULL,
	`comment` TEXT,
	`is_rated` TINYINT(1) DEFAULT 0,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collector_rating_FI_1` (`from_collector_id`),
	INDEX `collector_rating_FI_2` (`to_collector_id`),
	INDEX `collector_rating_FI_3` (`collectible_id`),
	CONSTRAINT `collector_rating_FK_1`
		FOREIGN KEY (`from_collector_id`)
		REFERENCES `collector` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `collector_rating_FK_2`
		FOREIGN KEY (`to_collector_id`)
		REFERENCES `collector` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `collector_rating_FK_3`
		FOREIGN KEY (`collectible_id`)
		REFERENCES `collectible` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

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

DROP TABLE IF EXISTS `collector_rating`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}
