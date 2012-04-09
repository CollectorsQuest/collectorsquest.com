<?php

class PropelMigration_1332876865
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

DROP TABLE IF EXISTS `collector_address`;
CREATE TABLE `collector_address`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collector_id` INTEGER NOT NULL,
	`full_name` VARCHAR(255) NOT NULL,
	`address_line_1` VARCHAR(255) NOT NULL,
	`address_line_2` VARCHAR(255),
	`city` VARCHAR(100) NOT NULL,
	`state_region` VARCHAR(100),
	`zip_postcode` VARCHAR(50),
	`country_iso3166` CHAR(2) NOT NULL,
	`phone` VARCHAR(50) NOT NULL,
	`is_primary` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `collector_address_FI_1` (`collector_id`),
	INDEX `collector_address_FI_2` (`country_iso3166`),
	CONSTRAINT `collector_address_FK_1`
		FOREIGN KEY (`collector_id`)
		REFERENCES `collector` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `collector_address_FK_2`
		FOREIGN KEY (`country_iso3166`)
		REFERENCES `geo_country` (`iso3166`)
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

DROP TABLE IF EXISTS `collector_address`;

',
);
	}

}
