<?php

ini_set('memory_limit', '512M');

/**
 * Fix oversized Mutlimedia for Collectibles
 */
class PropelMigration_1345640212
{

  public function preUp($manager)
  {
    if (sfConfig::get('sf_environment') === 'dev')
    {
      return;
    }

    $q = iceModelMultimediaQuery::create()
      ->filterByModel('Collectible')
      ->filterByType('image')
      ->orderByCreatedAt(Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $multimedia iceModelMultimedia[] */
    $multimedia = $q->find();

    /** @var $multimedia_count interger */
    $multimedia_count = count($multimedia);

    foreach ($multimedia as $k => $m)
    {
      if (
        !$m->fileExists('620x0') ||
        md5_file($m->getAbsolutePath('original')) === md5_file($m->getAbsolutePath('420x1000'))
      )
      {
        copy($m->getAbsolutePath('420x1000'), $m->getAbsolutePath('620x0'));
      }

      echo sprintf("\r Completed: %.2f%%", round($k/$multimedia_count, 4) * 100);
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
