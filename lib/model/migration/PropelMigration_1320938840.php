<?php

class PropelMigration_1320938840
{
  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preUp(PropelMigrationManager $manager)
  {
    /** @var $pdo PDO */
    $pdo = $manager->getPdoConnection('propel');

    $sql = "
      SELECT email, COUNT(collector.id) AS accounts
        FROM collector
       WHERE email IS NOT NULL AND email != ''
       GROUP BY email
      HAVING accounts > 1
       ORDER BY accounts DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $stmt->closeCursor();

    foreach ($emails as $email)
    {
      if (empty($email)) continue;

      $collectors = CollectorQuery::create()->filterByEmail($email)->find();
      for ($i=1; $i<count($collectors); $i++)
      {
        $collectors[$i]->setEmail(str_replace('@', '+'. $collectors[$i]->getId() .'@', $email));
        $collectors[$i]->save();
      }
    }

    /** @var $collectors Collector[] */
    $collectors = CollectorQuery::create()->filterByUsername('fb%', Criteria::LIKE)->find();
    foreach ($collectors as $collector)
    {
      $collector->setFacebookId(str_replace('fb', '', $collector->getUserName()));
      $collector->save();
    }
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
    return array("
      ALTER TABLE `collector` CHANGE `email` `email` VARCHAR(128) NULL DEFAULT NULL;
      UPDATE `collector` SET `email` = NULL WHERE `email` = '';

      ALTER TABLE `collector` ADD UNIQUE INDEX `collector_U_3` (`email`);
      ALTER TABLE `collector` ADD UNIQUE INDEX `collector_U_1` (`facebook_id`);

      ALTER TABLE `collector` CHANGE `slug` `slug` VARCHAR(64)  NULL  DEFAULT NULL;
      UPDATE collector SET slug = NULL WHERE slug = '';
      ALTER TABLE `collector` ADD UNIQUE INDEX `collector_U_2` (`slug`);
      UPDATE collector SET slug = username WHERE slug IS NULL;
      UPDATE collector SET display_name = username WHERE display_name = '';

      ALTER TABLE `collection` DROP INDEX collection_FI_1;
      ALTER TABLE `collection` DROP FOREIGN KEY collection_FK_1;
      ALTER TABLE `collection` CHANGE `collection_category_id` `collection_category_id` INT(11) NULL DEFAULT NULL;

      ALTER TABLE `collection_category` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `collection` ADD INDEX `collection_FI_1` (collection_category_id);
      ALTER TABLE `collection`
        ADD CONSTRAINT `collection_FK_1`
        FOREIGN KEY (`collection_category_id`)
        REFERENCES `collection_category` (`id`)
        ON DELETE SET NULL;
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
    return array();
  }
}
