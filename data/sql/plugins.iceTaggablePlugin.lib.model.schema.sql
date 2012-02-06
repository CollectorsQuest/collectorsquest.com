
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128),
	`slug` VARCHAR(255),
	`is_triple` TINYINT(1) DEFAULT 0,
	`triple_namespace` VARCHAR(128),
	`triple_key` VARCHAR(128),
	`triple_value` VARCHAR(128),
	PRIMARY KEY (`id`),
	INDEX `tag_I_1` (`name`),
	INDEX `tag_I_2` (`triple_namespace`, `triple_key`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- tagging
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tagging`;

CREATE TABLE `tagging`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`tag_id` INTEGER NOT NULL,
	`taggable_model` VARCHAR(50),
	`taggable_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `tagging_I_1` (`taggable_model`, `taggable_id`),
	INDEX `tagging_FI_1` (`tag_id`),
	CONSTRAINT `tagging_FK_1`
		FOREIGN KEY (`tag_id`)
		REFERENCES `tag` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
