<?php

class PropelMigration_1339093889
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
  }

  public function postUp($manager)
  {
    require_once __DIR__ .'/../../../plugins/iceLibsPlugin/lib/vendor/Gapi.class.php';

    /** @var $connection PropelPDO */
    $connection = $manager->getPdoConnection('propel');
    $ga = new Gapi('google@collectorsquest.com','JtNi1ZfP');

    $stmt = $connection->prepare('UPDATE `collectible` SET `num_views` = `num_views` + ? WHERE `id` = ?');

    $i = 1;
    while (true)
    {
      echo "Processing Google Analytics data [page ". $i ."]...\n";

      $ga->requestReportData(
        1111092, array('pagePath'), array('pageviews'),
        array('-pageviews'), "pagePath =~ /collectible/\d+", '2007-01-01', '2012-06-15',
        $i * 1000 - 999, 1000
      );

      if ($ga->getResults())
      {
        foreach ($ga->getResults() as $result)
        if (preg_match('~/collectible/(\d+)/~i', $result->getPagePath(), $m))
        {
          $stmt->execute(array($result->getPageviews(), (int) $m[1]));
        }

        $i++;
      }
      else
      {
        break;
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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;
        UPDATE `collectible` SET `num_views` = 0 WHERE 1;
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
    return array(
      'propel'  => '
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
