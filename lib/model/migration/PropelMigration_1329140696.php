<?php

class PropelMigration_1329140696
{
  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preUp(PropelMigrationManager $manager)
  {
    /** @var $pdo PDO */
    $pdo = $manager->getPdoConnection('propel');

    $sql = "UPDATE `collection_category` SET `id` = '0' WHERE `id` = '785' AND `name` = 'None';";
    $pdo->prepare($sql)->execute();

    $sql = "INSERT IGNORE INTO `collector_collection`
            SELECT id, graph_id, collection_category_id, collector_id, `name`, slug, description, num_items, num_views, num_comments, num_ratings,
                   score, is_public, is_featured, comments_on, rating_on, eblob, updated_at, created_at
              FROM `collection`;";
    $pdo->prepare($sql)->execute();

    $sql = "INSERT IGNORE INTO `collection_collectible`
            SELECT `collection_id`, `id`, `score`, `position`, `updated_at`, `created_at` FROM `collectible` WHERE collection_id IS NOT NULL;";
    $pdo->prepare($sql)->execute();
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
        ALTER TABLE `collection` DROP FOREIGN KEY `collection_FK_2`;
        ALTER TABLE `collection` DROP INDEX `collection_FI_2`;
        ALTER TABLE `collection` DROP INDEX `collection_FI_1`;
        ALTER TABLE `collection` DROP `collector_id`;

        ALTER TABLE `collectible` DROP FOREIGN KEY `collectible_FK_2`;
        ALTER TABLE `collectible` DROP INDEX `collectible_FI_2`;
        ALTER TABLE `collectible` DROP `collection_id`;
        ALTER TABLE `collectible` DROP `position`;
      ",
      'archive' => "
        ALTER TABLE `collection_archive`  DROP `collector_id`;
        ALTER TABLE `collectible_archive` DROP `collection_id`;
        ALTER TABLE `collectible_archive` DROP `position`;
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
    return array();
  }
}
