<?php

/**
 * Update the is_public status for all recent collections/collectibles
 */
class PropelMigration_1348610473
{

  public function preUp()
  {
    /** @var $q CollectorQuery */
    $q = CollectorQuery::create();

    /** @var $collections Collection[] */
    $collectors = $q->find();

    /** @var $count integer */
    $count = count($collectors);

    $count_changes = 0;

    foreach ($collectors as $k => $collector)
    {
      /* @var $collector Collector */
      $old_username = $collector->getUsername();
      $new_username = preg_replace('/\s+/', '',$old_username);
      if ($old_username != $new_username)
      {
        $collector->setUsername($new_username);
        $collector->save();
        $count_changes++;
      }


      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }

    echo sprintf('Changed %s records', $count_changes);
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
