<?php

/**
 * Update the is_public status for all recent collections/collectibles
 */
class PropelMigration_1348654810
{

  public function preUp()
  {
    $mc = cqStatic::getMailChimpClient();

    $q = CollectorArchiveQuery::create()
      ->orderBy('CreatedAt', Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectors Collector[] */
    $collectors = $q->find();

    $count = count($collectors);

    /* @var $collector Collector */
    foreach ($collectors as $k => $collector)
    {
      $mc->listUnsubscribe('4b51c2b29c', $collector->getEmail(), true);

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
