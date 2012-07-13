<?php

ini_set('memory_limit', '512M');

/**
 * Fix missing Mutlimedia sizes for Collectibles
 */
class PropelMigration_1342190796
{

  public function preUp($manager)
  {
    if (sfConfig::get('sf_environment') === 'dev') {
      return;
    }

    $q = iceModelMultimediaQuery::create()
      ->filterByModel('Collectible')
      ->filterByType('image')
      ->orderByCreatedAt(Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $multimedia iceModelMultimedia[] */
    $multimedia = $q->find();

    foreach ($multimedia as $i => $m)
    {
      if (!$m->fileExists('620x0'))
      {
        if (!$m->fileExists('original') && $m->fileExists('1024x768')) {
          copy($m->getAbsolutePath('1024x768'), $m->getAbsolutePath('original'));
        }
        else if (!$m->fileExists('original') && $m->fileExists('420x1000')) {
          copy($m->getAbsolutePath('420x1000'), $m->getAbsolutePath('original'));
        }

        if ($m->fileExists('original'))
        {
          $m->makeCustomThumb(190, 190, '190x190', 'top', false);
          $m->makeCustomThumb(190, 150, '190x150', 'top', false);
          $m->makeCustomThumb(260, 205, '260x205', 'top', false);
          $m->makeCustomThumb(620, 0, '620x0', 'resize', false);
          $m->save();

          echo ",";
        }
      }

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
