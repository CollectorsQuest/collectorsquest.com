<?php

class PropelMigration_1336314038
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

    $stmt = $connection->prepare('UPDATE `collector_profile` SET `num_views` = `num_views` + ? WHERE `collector_id` = ?');

    $i = 1;
    while (true)
    {
      echo "Processing Google Analytics data [page ". $i ."]...\n";

      $ga->requestReportData(
        1111092, array('pagePath'), array('pageviews'),
        array('-pageviews'), "pagePath =~ /collector/", '2007-01-01', '2012-04-30',
        $i * 1000 - 999, 1000
      );

      if ($ga->getResults())
      {
        foreach ($ga->getResults() as $result)
        {
          if (preg_match('~/collector/(\d+)/~i', $result->getPagePath(), $m))
          {
            $stmt->execute(array($result->getPageviews(), (int) $m[1]));
          }
          else if (preg_match('~/collector/(\w+)~i', $result->getPagePath(), $m))
          {
            $q = CollectorQuery::create()
               ->filterBySlug($m[1])
               ->setFormatter(ModelCriteria::FORMAT_STATEMENT)
               ->select('Collector.ID');

            if ($id = (int) $q->find()->fetchColumn(0))
            {
              $stmt->execute(array($result->getPageviews(), $id));
            }
          }
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
        ALTER TABLE `collector_profile` ADD `num_views` INT NOT NULL DEFAULT 0 AFTER `website`;
      ',
      'archive' => '
        ALTER TABLE `collector_profile_archive` ADD `num_views` INT NOT NULL DEFAULT 0 AFTER `website`;
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
        ALTER TABLE `collector_profile` DROP `num_views`;
      ',
      'archive' => '
        ALTER TABLE `collector_profile_archive` DROP `num_views`;
      '
    );
  }

}
