<?php

class PropelMigration_1321524757
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
      $collector->sendToImpermium('UPDATE');
      sleep(1);

      $collector->sendToDefensio('UPDATE');
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
    return array();
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
