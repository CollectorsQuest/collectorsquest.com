<?php

/**
 * Fixes the Video multimedia for A&E show pages
 */
class PropelMigration_1347434445
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    /** @var $q CollectibleQuery */
    $q = CollectibleQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    $aetn_shows = sfConfig::get('app_aetn_shows');
    foreach ($aetn_shows as $show)
    {
      $q->filterByCollectorId($show['collector'])
        ->_or();
    }

    $count = $q->count();
    $collectibles = $q->find();

    foreach ($collectibles as $k => $collectible)
    {
      /** @var $m iceModelMultimedia */
      $m = iceModelMultimediaPeer::retrieveByModel($collectible);

      $collectible->setEblobElement('multimedia', $m->toXML(true));
      $collectible->save();

      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }

    echo "\r Completed: 100%  \n";
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
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',

      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
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
    return array (
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
