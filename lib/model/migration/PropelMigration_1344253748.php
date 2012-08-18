<?php

/**
 * Assign random avatars to Collectors who do not have one currently
 */
class PropelMigration_1344253748
{

  public function preUp($manager)
  {
    $collectors_count = CollectorQuery::create()->count();
    $collectors = CollectorQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();

    echo "Assigning random avatars on collectors without any set\n";

    /** @var $collectors Collector[] */
    foreach ($collectors as $k => $collector)
    {
      if (null ===  $collector->getPrimaryImage())
      {
        $collector->assignRandomAvatar();
        $collector->save();
      }
      IceMultimediaBehavior::clearStaticCache();
      echo sprintf("\r Completed: %.2f%%", round($k/$collectors_count, 4) * 100);
    }

    echo "\r Completed: 100% Done!\n";
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
