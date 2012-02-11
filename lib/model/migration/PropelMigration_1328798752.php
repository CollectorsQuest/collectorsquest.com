<?php

class PropelMigration_1328798752
{
  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preUp(PropelMigrationManager $manager)
  {

  }

  /**
   * @param  PropelMigrationManager  $manager
   */
  public function postUp(PropelMigrationManager $manager)
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
    return array(
      'propel' => "
        DROP TABLE IF EXISTS `collection_collectible`;
        CREATE TABLE `collection_collectible`
        (
        	`collection_id` INTEGER NOT NULL,
        	`collectible_id` INTEGER NOT NULL,
        	`score` INTEGER DEFAULT 0,
        	`position` INTEGER DEFAULT 0,
        	`updated_at` DATETIME,
        	`created_at` DATETIME,
        	PRIMARY KEY (`collection_id`,`collectible_id`),
        	INDEX `collection_collectible_FI_2` (`collectible_id`),
        	CONSTRAINT `collection_collectible_FK_1`
        		FOREIGN KEY (`collection_id`)
        		REFERENCES `collection` (`id`)
        		ON DELETE CASCADE,
        	CONSTRAINT `collection_collectible_FK_2`
        		FOREIGN KEY (`collectible_id`)
        		REFERENCES `collectible` (`id`)
        		ON DELETE CASCADE
        ) ENGINE=InnoDB;

        DROP TABLE IF EXISTS `collector_collection`;
        CREATE TABLE `collector_collection`
        (
          `id` INTEGER NOT NULL,
        	`graph_id` INTEGER,
        	`collection_category_id` SMALLINT,
        	`collector_id` INTEGER NOT NULL,
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
        	PRIMARY KEY (`id`),
        	UNIQUE INDEX `collector_collection_U_1` (`graph_id`),
        	INDEX `collector_collection_FI_1` (`collector_id`),
        	INDEX `collector_collection_I_2` (`collection_category_id`),
        	CONSTRAINT `collector_collection_FK_1`
        		FOREIGN KEY (`collector_id`)
        		REFERENCES `collector` (`id`)
        		ON DELETE CASCADE,
        	CONSTRAINT `collector_collection_FK_2`
        		FOREIGN KEY (`id`)
        		REFERENCES `collection` (`id`)
        		ON DELETE CASCADE,
        	CONSTRAINT `collector_collection_FK_3`
        		FOREIGN KEY (`collection_category_id`)
        		REFERENCES `collection_category` (`id`)
        		ON DELETE SET NULL
        ) ENGINE=InnoDB;

        ALTER TABLE `collection` ADD `descendant_class` VARCHAR(100)  NULL  DEFAULT NULL  AFTER `collection_category_id`;
        UPDATE `collection` SET `descendant_class` = 'CollectorCollection' WHERE `descendant_class` IS NULL;
      ",
      'archive' => "
        ALTER TABLE `collection_archive` ADD `descendant_class` VARCHAR(100)  NULL  DEFAULT NULL  AFTER `collection_category_id`;
        UPDATE `collection_archive` SET `descendant_class` = 'CollectorCollection' WHERE `descendant_class` IS NULL;
      "
    );
  }

  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preDown(PropelMigrationManager $manager)
  {

  }

  /**
   * @param  PropelMigrationManager  $manager
   */
  public function postDown(PropelMigrationManager $manager)
  {

  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array(
      'propel' => "
        ALTER TABLE `collection` DROP `descendant_class`;
        DROP TABLE IF EXISTS `collection_collectible`;
        DROP TABLE IF EXISTS `collector_collection`;
      ",
      'archive' => "
        ALTER TABLE `collection_archive` DROP `descendant_class`;
      "
    );
  }
}
