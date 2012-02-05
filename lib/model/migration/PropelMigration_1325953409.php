<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1325953409.
 * Generated on 2012-01-07 11:23:29 by root
 */
class PropelMigration_1325953409
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
		return array(
      'propel' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collectible` CHANGE `name` `name` VARCHAR(255) NOT NULL;
        ALTER TABLE `collectible` CHANGE `num_comments` `num_comments` INTEGER NOT NULL DEFAULT 0;
        ALTER TABLE `collectible` CHANGE `score` `score` INTEGER NOT NULL DEFAULT 0;
        ALTER TABLE `collectible` CHANGE `position` `position` INTEGER NOT NULL DEFAULT 0;
        ALTER TABLE `collectible` ADD `eblob` TEXT NULL AFTER `is_name_automatic`;

        ALTER TABLE `collection` CHANGE `name` `name` VARCHAR(255) NOT NULL;
        ALTER TABLE `collection` CHANGE `slug` `slug` VARCHAR(128);
        ALTER TABLE `collection` CHANGE `num_items` `num_items` INTEGER DEFAULT 0;
        ALTER TABLE `collection` CHANGE `num_views` `num_views` INTEGER DEFAULT 0;
        ALTER TABLE `collection` CHANGE `num_comments` `num_comments` INTEGER DEFAULT 0;
        ALTER TABLE `collection` CHANGE `num_ratings` `num_ratings` INTEGER DEFAULT 0;
        ALTER TABLE `collection` CHANGE `score` `score` INTEGER DEFAULT 0 NOT NULL;
        ALTER TABLE `collection` ADD `eblob` TEXT NULL AFTER `rating_on`;

        ALTER TABLE `collection_category` CHANGE `name` `name` VARCHAR(64) NOT NULL;
        ALTER TABLE `collection_category` CHANGE `slug` `slug` VARCHAR(64);

        ALTER TABLE `collector` CHANGE `username` `username` VARCHAR(64) NOT NULL;
        ALTER TABLE `collector` CHANGE `slug` `slug` VARCHAR(64);
        ALTER TABLE `collector` CHANGE `sha1_password` `sha1_password` VARCHAR(40) NOT NULL;
        ALTER TABLE `collector` CHANGE `salt` `salt` VARCHAR(32) NOT NULL;

        ALTER TABLE `collector` CHANGE `score` `score` INTEGER DEFAULT 0;
        ALTER TABLE `collector` CHANGE `spam_score` `spam_score` INTEGER DEFAULT 0;
        ALTER TABLE `collector` CHANGE `is_spam` `is_spam` TINYINT(1) DEFAULT 0;
        ALTER TABLE `collector` CHANGE `is_public` `is_public` TINYINT(1) DEFAULT 1;

        ALTER TABLE `collector` CHANGE `session_id` `session_id` VARCHAR(32);
        ALTER TABLE `collector` CHANGE `last_seen_at` `last_seen_at` DATETIME;
        ALTER TABLE `collector` ADD `eblob` TEXT NULL AFTER `last_seen_at`;

        ALTER TABLE `collector_profile` CHANGE `collector_id` `collector_id` INTEGER NOT NULL;
        ALTER TABLE `collector_profile` CHANGE `country_iso3166` `country_iso3166` VARCHAR(2);
        ALTER TABLE `collector_profile` CHANGE `is_featured` `is_featured` TINYINT(1) DEFAULT 0;
        ALTER TABLE `collector_profile` CHANGE `is_seller` `is_seller` TINYINT(1) DEFAULT 0;
        ALTER TABLE `collector_profile` CHANGE `is_image_auto` `is_image_auto` TINYINT(1) DEFAULT 1 NOT NULL;

        ALTER TABLE `collector_friend` CHANGE `collector_id` `collector_id` INTEGER NOT NULL;
        ALTER TABLE `collector_friend` CHANGE `friend_id` `friend_id` INTEGER NOT NULL;

        ALTER TABLE `collector_interview` CHANGE `catch_phrase` `catch_phrase` VARCHAR(128) NOT NULL;
        ALTER TABLE `collector_interview` CHANGE `is_active` `is_active` TINYINT(1) DEFAULT 0;

        ALTER TABLE `playlist` CHANGE `title` `title` VARCHAR(64) NOT NULL;
        ALTER TABLE `playlist` CHANGE `description` `description` TEXT NOT NULL;
        ALTER TABLE `playlist` CHANGE `is_published` `is_published` TINYINT(1);

        ALTER TABLE `sf_guard_user` CHANGE `is_active` `is_active` TINYINT(1) DEFAULT 1 NOT NULL;
        ALTER TABLE `sf_guard_user` CHANGE `is_super_admin` `is_super_admin` TINYINT(1) DEFAULT 0 NOT NULL;

        ALTER TABLE `tagging` CHANGE `taggable_model` `taggable_model` VARCHAR(50);

        ALTER TABLE `tag` CHANGE `name` `name` VARCHAR(128);
        ALTER TABLE `tag` CHANGE `slug` `slug` VARCHAR(255);
        ALTER TABLE `tag` CHANGE `is_triple` `is_triple` TINYINT(1) DEFAULT 0;
        ALTER TABLE `tag` CHANGE `triple_namespace` `triple_namespace` VARCHAR(128);
        ALTER TABLE `tag` CHANGE `triple_key` `triple_key` VARCHAR(128);
        ALTER TABLE `tag` CHANGE `triple_value` `triple_value` VARCHAR(128);

        DROP INDEX `triple1` ON `tag`;
        DROP INDEX `triple2` ON `tag`;
        DROP INDEX `triple3` ON `tag`;
        CREATE INDEX `tag_I_2` ON `tag` (`triple_namespace`,`triple_key`);

        ALTER TABLE `video` CHANGE `title` `title` VARCHAR(64) NOT NULL;
        ALTER TABLE `video` CHANGE `description` `description` TEXT NOT NULL;
        ALTER TABLE `video` CHANGE `type` `type` VARCHAR(64) NOT NULL;
        ALTER TABLE `video` CHANGE `is_published` `is_published` TINYINT(1);

        ALTER TABLE `term_relationship` ADD CONSTRAINT `term_relationship_FK_1`
        	FOREIGN KEY (`term_id`)
        	REFERENCES `term` (`id`);

        ALTER TABLE `featured` CHANGE `featured_type_id` `featured_type_id` TINYINT NOT NULL;
        ALTER TABLE `featured` CHANGE `is_active` `is_active` TINYINT(1) DEFAULT 1 NOT NULL;
        ALTER TABLE `featured` CHANGE `position` `position` TINYINT DEFAULT 0 NOT NULL;

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
		return array();
	}

}
