<?php

class PropelMigration_1339506996
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    /* @var $profiles CollectorProfile[] */
    $profiles = CollectorProfileQuery::create()
        ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
        ->find();

    foreach ($profiles as $profile)
    {
      $profile->updateProfileProgress();
    }
  }

  public function postUp($manager)
  {

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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 1;
      ',
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
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}
