<?php

/**
 * Update Shipping rates related talbes
 * (in preparation for RocketShipIt integration)
 *
 * Generated on 2012-06-18 09:07:13 by root
 */
class PropelMigration_1340024833
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

DROP TABLE `shipping_rate_collector`;
DROP TABLE `shipping_rate_collectible`;

CREATE TABLE `shipping_reference`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`model` CHAR(64) NOT NULL,
	`model_id` INTEGER NOT NULL,
	`country_iso3166` CHAR(2) NOT NULL,
	`shipping_type` TINYINT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `foreign_model_country` (`model`, `model_id`, `country_iso3166`),
	INDEX `shipping_reference_FI_1` (`country_iso3166`),
	CONSTRAINT `shipping_reference_FK_1`
		FOREIGN KEY (`country_iso3166`)
		REFERENCES `geo_country` (`iso3166`)
) ENGINE=InnoDB;

CREATE TABLE `shipping_rate`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`shipping_reference_id` INTEGER NOT NULL,
	`shipping_carrier_service_id` INTEGER,
	`flat_rate_in_cents` INTEGER,
	`is_free_shipping` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `shipping_rate_FI_1` (`shipping_reference_id`),
	INDEX `shipping_rate_FI_2` (`shipping_carrier_service_id`),
	CONSTRAINT `shipping_rate_FK_1`
		FOREIGN KEY (`shipping_reference_id`)
		REFERENCES `shipping_reference` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `shipping_rate_FK_2`
		FOREIGN KEY (`shipping_carrier_service_id`)
		REFERENCES `shipping_carrier_service` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `shipping_carrier_service`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`carrier` TINYINT NOT NULL,
	`service_name` VARCHAR(255) NOT NULL,
	`service_key` VARCHAR(255) NOT NULL,
	`is_international` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`)
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

DROP TABLE `shipping_carrier_service`;
DROP TABLE `shipping_rate`;
DROP TABLE `shipping_reference`;

CREATE TABLE `shipping_rate_collectible`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collectible_id` INTEGER NOT NULL,
	`country_iso3166` CHAR(2) NOT NULL,
	`calculation_type` TINYINT NOT NULL,
	`price_range_min` INTEGER NOT NULL,
	`price_range_max` INTEGER NOT NULL,
	`amount_in_cents` INTEGER NOT NULL,
	`amount_in_percent` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `shipping_rate_collectible_FI_1` (`collectible_id`),
	INDEX `shipping_rate_collectible_I_2` (`country_iso3166`(2)),
	CONSTRAINT `shipping_rate_collectible_FK_1`
		FOREIGN KEY (`collectible_id`)
		REFERENCES `collectible` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `shipping_rate_collectible_FK_2`
		FOREIGN KEY (`country_iso3166`)
		REFERENCES `geo_country` (`iso3166`)
) ENGINE=InnoDB;

CREATE TABLE `shipping_rate_collector`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collector_id` INTEGER NOT NULL,
	`country_iso3166` CHAR(2) NOT NULL,
	`calculation_type` TINYINT NOT NULL,
	`price_range_min` INTEGER NOT NULL,
	`price_range_max` INTEGER NOT NULL,
	`amount_in_cents` INTEGER NOT NULL,
	`amount_in_percent` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `shipping_rate_collector_FI_1` (`collector_id`),
	INDEX `shipping_rate_collector_I_2` (`country_iso3166`(2)),
	CONSTRAINT `shipping_rate_collector_FK_1`
		FOREIGN KEY (`collector_id`)
		REFERENCES `collector` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `shipping_rate_collector_FK_2`
		FOREIGN KEY (`country_iso3166`)
		REFERENCES `geo_country` (`iso3166`)
) ENGINE=InnoDB;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}