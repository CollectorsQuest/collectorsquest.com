<?php

class PropelMigration_1331821763
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

        DROP TABLE IF EXISTS `collector_remember_key`;
        CREATE TABLE `collector_remember_key`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `collector_id` INTEGER NOT NULL,
          `remember_key` CHAR(32),
          `ip_address` CHAR(15),
          `created_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `collector_remember_key_FI_1` (`collector_id`),
          CONSTRAINT `collector_remember_key_FK_1`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        ALTER TABLE `collector_identifier` DROP FOREIGN KEY `collector_identifier_FK_1`;
        ALTER TABLE `collector_identifier` DROP INDEX `collector_identifier_U_1`;

        ALTER TABLE `collector_identifier`
          ADD CONSTRAINT `collector_identifier_FK_1`
          FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE;

        ALTER TABLE `collector_identifier`
          ADD UNIQUE INDEX `collector_identifier_U_1` (`identifier`);
      ',

      'archive' => '
        ALTER TABLE `collectible_archive` DROP INDEX `collectible_archive_I_1`;
        ALTER TABLE `collector_identifier_archive` DROP INDEX `collector_identifier_archive_I_1`;
        ALTER TABLE `collectible_for_sale_archive` DROP INDEX `collectible_for_sale_archive_I_1`;
        ALTER TABLE `collectible_offer_archive` DROP INDEX `collectible_offer_archive_I_1`;
        ALTER TABLE `collection_archive` DROP INDEX `collection_archive_I_1`;
        ALTER TABLE `collector_archive` DROP INDEX `collector_archive_I_1`;
        ALTER TABLE `collector_collection_archive` DROP INDEX `collector_collection_archive_I_1`;
        ALTER TABLE `collector_geocache_archive` DROP INDEX `collector_geocache_archive_I_1`;
        ALTER TABLE `comment_archive` DROP INDEX `comment_archive_I_1`;
        ALTER TABLE `multimedia_archive` DROP INDEX `multimedia_archive_I_1`;
      '
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
		return array ();
	}

}
