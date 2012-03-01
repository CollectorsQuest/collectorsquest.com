<?php

class PropelMigration_1329991663
{
	public function preUp($manager)
	{

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
      'archive' => '
        DROP TABLE IF EXISTS `collector_collection_archive`;
        CREATE TABLE `collector_collection_archive`
        (
          `id` INTEGER NOT NULL,
          `graph_id` INTEGER,
          `collector_id` INTEGER,
          `collection_category_id` INTEGER,
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
          INDEX `collector_collection_archive_I_1` (`id`),
          INDEX `collector_collection_archive_I_2` (`graph_id`),
          INDEX `collector_collection_archive_I_3` (`collector_id`),
          INDEX `collector_collection_archive_I_4` (`collection_category_id`)
        ) ENGINE=MyISAM;
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
      'archive' => '
        DROP TABLE IF EXISTS `collector_collection_archive`;
      ',
    );
	}

}
