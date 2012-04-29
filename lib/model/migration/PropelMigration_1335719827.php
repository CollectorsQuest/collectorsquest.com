<?php

class PropelMigration_1335719827
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
        array('-pageviews'), "pagePath =~ /collectible/\d+", '2007-01-01', '2012-04-30',
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
        ALTER TABLE `collectible` MODIFY COLUMN `num_comments` INT(11) NOT NULL DEFAULT 0 AFTER `batch_hash`;
        ALTER TABLE `collectible` ADD `num_views` INT  NOT NULL  DEFAULT 0  AFTER `batch_hash`;
      ',
      'archive' => '
        ALTER TABLE `collectible_archive` MODIFY COLUMN `num_comments` INT(11) NOT NULL DEFAULT 0 AFTER `batch_hash`;
        ALTER TABLE `collectible_archive` ADD `num_views` INT  NOT NULL  DEFAULT 0  AFTER `batch_hash`;
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
        ALTER TABLE `collectible` DROP `num_views`;
      ',
      'archive' => '
        ALTER TABLE `collectible_archive` DROP `num_views`;
      '
    );
  }

}
