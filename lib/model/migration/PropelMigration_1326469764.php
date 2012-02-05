<?php

class PropelMigration_1326469764
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
    return array('propel' => "
      ALTER TABLE `collector` ADD `graph_id` INTEGER NULL DEFAULT NULL AFTER `id`;
      ALTER TABLE `collector` ADD UNIQUE INDEX `collector_U_2` (`graph_id`);

      ALTER TABLE `collection` ADD `graph_id` INTEGER NULL DEFAULT NULL AFTER `id`;
      ALTER TABLE `collection` ADD UNIQUE INDEX `collection_U_1` (`graph_id`);

      ALTER TABLE `collectible` ADD `graph_id` INTEGER NULL DEFAULT NULL AFTER `id`;
      ALTER TABLE `collectible` ADD UNIQUE INDEX `collectible_U_1` (`graph_id`);
    ");
  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array('propel' => "
      ALTER TABLE `collector` DROP `graph_id`;
      ALTER TABLE `collection` DROP `graph_id`;
      ALTER TABLE `collectible` DROP `graph_id`;
    ");
  }
}
