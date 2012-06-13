<?php

class PropelMigration_1337016152
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    if (sfConfig::get('sf_environment') === 'dev') {
      return true;
    }

    $q = CollectorCollectionQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->orderBy('CreatedAt', Criteria::DESC);

    /** @var $collections CollectorCollection[] */
    $collections = $q->find();
    foreach ($collections as $collection)
    {
      echo "Creating thumbs for collection: ". $collection->getName() ."\n";

      if ($multimedia = $collection->getPrimaryImage())
      {
        $collection->createMultimediaThumbs($multimedia, array('watermark' => false));
      }
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
