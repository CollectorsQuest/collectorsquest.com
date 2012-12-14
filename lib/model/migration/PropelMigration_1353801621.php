<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1353801621.
 * Generated on 2012-11-24 19:00:21 by root
 */
class PropelMigration_1353801621
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
		// add the post-migration code here
    $collections_count = CollectionQuery::create()
      ->count();
    $collections = CollectionQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();

    foreach ($collections as $k => $collection)
    {
      /* @var $collection Collection */
      if (( $collector_collection = $collection->getCollectorCollection() ))
      {
        /* @var $collector_collection CollectorCollection */
        // if the collection has a related collector collection, update it
        // and rely on cocrete inheritance's cascading parent update
        $collector_collection->updateNumPublicItems();
      }
      else
      {
        // otherwise just update the Collection
        $collection->updateNumPublicItems();
      }

      echo sprintf("\r Completed: %.2f%%", round($k/$collections_count, 4) * 100);
    }

    echo "\r Completed: 100.00% \n";
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

        ALTER TABLE `collection` ADD `num_public_items` INTEGER DEFAULT 0 AFTER `num_items`;
        ALTER TABLE `collector_collection` ADD `num_public_items` INTEGER DEFAULT 0 AFTER `num_items`;

        ALTER TABLE `collection_archive` ADD `num_public_items` INTEGER DEFAULT 0 AFTER `num_items`;
        ALTER TABLE `collector_collection_archive` ADD `num_public_items` INTEGER DEFAULT 0 AFTER `num_items`;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
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

        ALTER TABLE `collection` DROP `num_public_items`;
        ALTER TABLE `collector_collection` DROP `num_public_items`;

        ALTER TABLE `collection_archive` DROP `num_public_items`;
        ALTER TABLE `collector_collection_archive` DROP `num_public_items`;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
	}

}