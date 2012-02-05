
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- collector_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collector_archive`;

CREATE TABLE `collector_archive`
(
	`id` INTEGER NOT NULL,
	`graph_id` INTEGER,
	`facebook_id` VARCHAR(20),
	`username` VARCHAR(64) NOT NULL,
	`display_name` VARCHAR(64) NOT NULL,
	`slug` VARCHAR(64) NOT NULL,
	`sha1_password` VARCHAR(40) NOT NULL,
	`salt` VARCHAR(32) NOT NULL,
	`email` VARCHAR(128),
	`user_type` ENUM('Collector','Seller') DEFAULT 'Collector' NOT NULL,
	`items_allowed` INTEGER,
	`what_you_collect` VARCHAR(255),
	`purchases_per_year` INTEGER DEFAULT 0,
	`what_you_sell` VARCHAR(255),
	`annually_spend` FLOAT DEFAULT 0,
	`most_expensive_item` FLOAT DEFAULT 0,
	`company` VARCHAR(255),
	`locale` VARCHAR(5) DEFAULT 'en_US',
	`score` INTEGER DEFAULT 0,
	`spam_score` INTEGER DEFAULT 0,
	`is_spam` TINYINT(1) DEFAULT 0,
	`is_public` TINYINT(1) DEFAULT 1,
	`session_id` VARCHAR(32),
	`last_seen_at` DATETIME,
	`eblob` TEXT,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collector_archive_I_1` (`id`),
	INDEX `collector_archive_I_2` (`graph_id`),
	INDEX `collector_archive_I_3` (`facebook_id`),
	INDEX `collector_archive_I_4` (`slug`),
	INDEX `collector_archive_I_5` (`email`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collector_profile_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collector_profile_archive`;

CREATE TABLE `collector_profile_archive`
(
	`id` INTEGER NOT NULL,
	`collector_id` INTEGER,
	`collector_type` ENUM('Collector','Seller') DEFAULT 'Collector' NOT NULL,
	`birthday` DATE,
	`gender` ENUM('f','m'),
	`zip_postal` VARCHAR(10),
	`country` VARCHAR(64),
	`country_iso3166` VARCHAR(2),
	`website` VARCHAR(128),
	`about` TEXT,
	`collections` TEXT,
	`collecting` VARCHAR(255),
	`most_spent` INTEGER,
	`anually_spent` INTEGER,
	`new_item_every` VARCHAR(64),
	`interests` TEXT,
	`is_featured` TINYINT(1) DEFAULT 0,
	`is_seller` TINYINT(1) DEFAULT 0,
	`is_image_auto` TINYINT(1) DEFAULT 1,
	`preferences` TEXT,
	`notifications` TEXT,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collector_profile_archive_I_1` (`id`),
	INDEX `collector_profile_archive_I_2` (`collector_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collector_identifier_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collector_identifier_archive`;

CREATE TABLE `collector_identifier_archive`
(
	`id` INTEGER NOT NULL,
	`collector_id` INTEGER,
	`identifier` VARCHAR(255),
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collector_identifier_archive_I_1` (`id`),
	INDEX `collector_identifier_archive_I_2` (`collector_id`),
	INDEX `collector_identifier_archive_I_3` (`identifier`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collector_geocache_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collector_geocache_archive`;

CREATE TABLE `collector_geocache_archive`
(
	`id` INTEGER NOT NULL,
	`collector_id` INTEGER,
	`country` CHAR(64),
	`country_iso3166` CHAR(2),
	`state` VARCHAR(64),
	`county` VARCHAR(64),
	`city` VARCHAR(64),
	`zip_postal` CHAR(10),
	`address` VARCHAR(128),
	`latitude` DECIMAL(8,5),
	`longitude` DECIMAL(8,5),
	`timezone` VARCHAR(128),
	PRIMARY KEY (`id`),
	INDEX `collector_geocache_archive_I_1` (`id`),
	INDEX `collector_geocache_archive_I_2` (`collector_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collection_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collection_archive`;

CREATE TABLE `collection_archive`
(
	`id` INTEGER NOT NULL,
	`graph_id` INTEGER,
	`collection_category_id` INTEGER,
	`collector_id` INTEGER,
	`name` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(128),
	`description` TEXT NOT NULL,
	`num_items` INTEGER DEFAULT 0,
	`num_views` INTEGER DEFAULT 0,
	`num_comments` INTEGER DEFAULT 0,
	`num_ratings` INTEGER DEFAULT 0,
	`score` INTEGER DEFAULT 0,
	`is_public` TINYINT(1) DEFAULT 1,
	`is_featured` TINYINT(1) DEFAULT 0,
	`comments_on` TINYINT(1) DEFAULT 1,
	`rating_on` TINYINT(1) DEFAULT 1,
	`eblob` TEXT,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collection_archive_I_1` (`id`),
	INDEX `collection_archive_I_2` (`graph_id`),
	INDEX `collection_archive_I_3` (`collection_category_id`),
	INDEX `collection_archive_I_4` (`collector_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collectible_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collectible_archive`;

CREATE TABLE `collectible_archive`
(
	`id` INTEGER NOT NULL,
	`graph_id` INTEGER,
	`collector_id` INTEGER,
	`collection_id` INTEGER,
	`name` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(128),
	`description` TEXT NOT NULL,
	`num_comments` INTEGER DEFAULT 0,
	`batch_hash` VARCHAR(32),
	`score` INTEGER DEFAULT 0,
	`position` INTEGER DEFAULT 0,
	`is_name_automatic` TINYINT(1) DEFAULT 0,
	`eblob` TEXT,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collectible_archive_I_1` (`id`),
	INDEX `collectible_archive_I_2` (`graph_id`),
	INDEX `collectible_archive_I_3` (`collector_id`),
	INDEX `collectible_archive_I_4` (`collection_id`),
	INDEX `collectible_archive_I_5` (`slug`),
	INDEX `collectible_archive_I_6` (`batch_hash`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collectible_for_sale_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collectible_for_sale_archive`;

CREATE TABLE `collectible_for_sale_archive`
(
	`id` INTEGER NOT NULL,
	`collectible_id` INTEGER,
	`price` FLOAT,
	`condition` ENUM('excellent','very good','good','fair','poor') NOT NULL,
	`is_price_negotiable` TINYINT(1) DEFAULT 0,
	`is_shipping_free` TINYINT(1) DEFAULT 0,
	`is_sold` TINYINT(1) DEFAULT 0,
	`is_ready` TINYINT(1) DEFAULT 0 COMMENT 'Show in the market or no',
	`quantity` INTEGER DEFAULT 1 NOT NULL,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collectible_for_sale_archive_I_1` (`id`),
	INDEX `collectible_for_sale_archive_I_2` (`collectible_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- collectible_offer_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `collectible_offer_archive`;

CREATE TABLE `collectible_offer_archive`
(
	`id` INTEGER NOT NULL,
	`collectible_id` INTEGER,
	`collectible_for_sale_id` INTEGER,
	`collector_id` INTEGER,
	`price` FLOAT,
	`status` ENUM('pending','counter','rejected','accepted') NOT NULL,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `collectible_offer_archive_I_1` (`id`),
	INDEX `collectible_offer_archive_I_2` (`collectible_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- comment_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comment_archive`;

CREATE TABLE `comment_archive`
(
	`id` INTEGER NOT NULL,
	`disqus_id` CHAR(10),
	`parent_id` CHAR(10),
	`collection_id` INTEGER,
	`collectible_id` INTEGER,
	`collector_id` INTEGER,
	`author_name` VARCHAR(128),
	`author_email` VARCHAR(128),
	`author_url` VARCHAR(255),
	`subject` VARCHAR(128),
	`body` TEXT NOT NULL,
	`ip_address` VARCHAR(15),
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `comment_archive_I_1` (`id`),
	INDEX `comment_archive_I_2` (`collection_id`),
	INDEX `comment_archive_I_3` (`collectible_id`),
	INDEX `comment_archive_I_4` (`collector_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- multimedia_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `multimedia_archive`;

CREATE TABLE `multimedia_archive`
(
	`id` INTEGER NOT NULL,
	`model` CHAR(64) NOT NULL,
	`model_id` INTEGER,
	`type` ENUM('image','video') DEFAULT 'image' NOT NULL,
	`name` VARCHAR(128),
	`md5` CHAR(32) NOT NULL,
	`colors` VARCHAR(128),
	`orientation` ENUM('landscape','portrait') DEFAULT 'landscape',
	`source` VARCHAR(255),
	`is_primary` TINYINT(1) DEFAULT 0,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`archived_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `multimedia_archive_I_1` (`id`),
	INDEX `multimedia_I_1` (`model`, `model_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
