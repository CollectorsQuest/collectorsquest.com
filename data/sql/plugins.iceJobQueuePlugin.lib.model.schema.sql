
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- job_queue
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `job_queue`;

CREATE TABLE `job_queue`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`unique_key` CHAR(64),
	`function_name` VARCHAR(255),
	`priority` SMALLINT UNSIGNED DEFAULT 1 NOT NULL,
	`data` LONGBLOB NOT NULL,
	`when_to_run` BIGINT UNSIGNED DEFAULT 0 NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `job_queue_U_1` (`unique_key`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- job_run
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `job_run`;

CREATE TABLE `job_run`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`crontab_id` INTEGER,
	`context` ENUM('global', 'icepique', 'autohop', 'bezplatno', 'burzo', 'hitimoti', 'netbox', 'torena') DEFAULT 'global' NOT NULL,
	`unique_key` CHAR(64) NOT NULL,
	`job_handle` CHAR(64) NOT NULL,
	`function_name` VARCHAR(255),
	`completed` INTEGER DEFAULT 0,
	`total` INTEGER DEFAULT 0,
	`status` ENUM('pending', 'queued', 'running', 'cancelled', 'completed', 'failed') DEFAULT 'pending' NOT NULL,
	`cpu_stats` TEXT NOT NULL,
	`memory_stats` TEXT NOT NULL,
	`loadavg_stats` TEXT NOT NULL,
	`priority` SMALLINT UNSIGNED DEFAULT 1 NOT NULL,
	`is_background` TINYINT(1) DEFAULT 0 NOT NULL,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `job_run_U_1` (`unique_key`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
