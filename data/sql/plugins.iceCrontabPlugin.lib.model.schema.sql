
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- crontab
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `crontab`;

CREATE TABLE `crontab`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`context` ENUM('global', 'icepique', 'autohop', 'bezplatno', 'burzo', 'hitimoti', 'netbox', 'torena') DEFAULT 'global' NOT NULL,
	`minute` CHAR(2) DEFAULT '1' NOT NULL,
	`hour` CHAR(2) DEFAULT '5' NOT NULL,
	`month` CHAR(2) DEFAULT '*' NOT NULL,
	`day_of_week` CHAR(2) DEFAULT '*' NOT NULL,
	`day_of_month` CHAR(2) DEFAULT '*' NOT NULL,
	`function_name` VARCHAR(255) NOT NULL,
	`parameters` VARCHAR(255),
	`description` TEXT,
	`priority` SMALLINT UNSIGNED DEFAULT 1 NOT NULL,
	`is_active` BOOL DEFAULT 1,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
