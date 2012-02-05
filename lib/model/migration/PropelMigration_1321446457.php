<?php

class PropelMigration_1321446457
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
    /** @var $collectors Collector[] */
    $collectors = CollectorQuery::create()->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)->find();
    foreach ($collectors as $collector)
    {
      $collector->sendToImpermium();
      sleep(1);
    }
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
      ALTER TABLE `collector` MODIFY COLUMN `score` INT(11) NOT NULL DEFAULT '0' AFTER `company`;
      ALTER TABLE `collector` ADD `spam_score` INT  NULL  DEFAULT NULL  AFTER `score`;
      ALTER TABLE `collector` CHANGE `spam_score` `spam_score` INT(11)  NULL  DEFAULT '0';
      ALTER TABLE `collector` ADD `is_spam` TINYINT(4)  NOT NULL  DEFAULT '0'  AFTER `spam_score`;
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
