<?php

class PropelMigration_1337016158
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp()
  {
    $q = CollectibleQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->orderBy('CreatedAt', Criteria::ASC)
      ->offset(50000)
      ->limit(10000);

    /** @var $collectibles Collectible[] */
    $collectibles = $q->find();
    foreach ($collectibles as $i => $collectible)
    {
      echo $i .", ";

      if ($multimedia = $collectible->getMultimedia(0, 'image'))
      foreach($multimedia as $m)
      {
        $collectible->createMultimediaThumbs($m, array('watermark' => false));
      }

      if ($i % 100 === 0) sleep(5);
    }
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
    return array(
      'propel'  => "
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
