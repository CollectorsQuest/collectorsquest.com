<?php

/**
 * Fix CollectorProfile preferences for the newsletter
 */
class PropelMigration_1342724534
{

  public function preUp($manager)
  {
    $q = CollectorProfileQuery::create()
      ->orderByCreatedAt(Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $profiles CollectorProfile[] */
    $profiles = $q->find();

    foreach ($profiles as $i => $profile)
    {
      $preferences = $profile->getPreferences();
      $preferences['newsletter'] = true;
      $profile->setPreferences($preferences);
      $profile->save();

      if ($i % 100 === 0) {
        echo ".";
      }
    }

    echo "\n";
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
