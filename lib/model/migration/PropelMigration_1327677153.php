<?php

class PropelMigration_1327677153
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
        ALTER TABLE `collectible` CHANGE `collection_id` `collection_id` INTEGER;
        ALTER TABLE `collectible` DROP FOREIGN KEY `collectible_FK_2`;
        ALTER TABLE `collectible` DROP INDEX `collectible_FI_2`;

        CREATE INDEX `collectible_FI_2` ON `collectible` (`collection_id`);
        ALTER TABLE `collectible` ADD CONSTRAINT `collectible_FK_2`
          FOREIGN KEY (`collection_id`)
          REFERENCES `collection` (`id`)
          ON DELETE SET NULL;
      "
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
    return array(
      'propel' => "
        ALTER TABLE `collectible` CHANGE `collection_id` `collection_id` INTEGER NOT NULL;
        ALTER TABLE `collectible` DROP FOREIGN KEY `collectible_FK_2`;
        ALTER TABLE `collectible` DROP INDEX `collectible_FI_2`;

        CREATE INDEX `collectible_FI_2` ON `collectible` (`collection_id`);
        ALTER TABLE `collectible` ADD CONSTRAINT `collectible_FK_2`
          FOREIGN KEY (`collection_id`)
          REFERENCES `collection` (`id`)
          ON DELETE CASCADE;
      "
    );
  }
}
