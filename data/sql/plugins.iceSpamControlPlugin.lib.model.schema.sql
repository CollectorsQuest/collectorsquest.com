
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- spam_control
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `spam_control`;

CREATE TABLE `spam_control`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`field` ENUM('email','phone','ip','regex','session') DEFAULT 'regex' NOT NULL,
	`value` VARCHAR(64) NOT NULL,
	`credentials` SET('read', 'create', 'edit', 'comment') DEFAULT 'read' NOT NULL,
	`is_banned` BOOL DEFAULT 0 NOT NULL,
	`is_throttled` BOOL DEFAULT 0 NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `spam_control_U_1` (`field`, `value`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
