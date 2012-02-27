<?php

class testGenerateFixturesTask extends sfBaseTask
{
  /** @var sfProjectConfiguration */
  protected $configuration = null;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'test';
    $this->name      = 'generate-fixtures';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    /** @var $propel PropelPDO */
    $propel = $databaseManager->getDatabase('propel')->getConnection();

    /** @var $archive PropelPDO */
    $archive = $databaseManager->getDatabase('archive')->getConnection();

    $collector_ids = array(531, 52, 1082, 3632, 6610, 1374, 12, 714, 235, 870, 1317, 163, 963, 644, 59, 4208, 6700, 1212, 4295, 846, 9367);

    /** @var $sqls array */
    $sqls = array();

    $sqls['propel'] = array(
      "DELETE FROM collectible_offer WHERE collector_id NOT IN (". implode(',', $collector_ids) .");",
      "DELETE FROM collectible WHERE collector_id NOT IN (". implode(',', $collector_ids) .");",
      "DELETE FROM collectible_archive WHERE collector_id NOT IN (". implode(',', $collector_ids) .");",
      "DELETE FROM collection_collectible WHERE collectible_id NOT IN (SELECT id FROM collectible)",
      "DELETE FROM collector_collection WHERE collector_id NOT IN (". implode(',', $collector_ids) .");",
      "DELETE FROM collection WHERE id NOT IN (SELECT id FROM collector_collection) AND descendant_class = 'CollectorCollection';",
      "DELETE FROM collection_archive WHERE id NOT IN (SELECT id FROM collector_collection) AND descendant_class = 'CollectorCollection';",
      "DELETE FROM collector WHERE id NOT IN (". implode(',', $collector_ids) .");",
      "DELETE FROM collector_extra_property WHERE collector_id NOT IN (SELECT id FROM collector);",
      "DELETE FROM collector_profile WHERE collector_id NOT IN (SELECT id FROM collector);",
      "DELETE FROM collector_profile_extra_property WHERE collector_profile_collector_id NOT IN (SELECT collector_id FROM collector_profile);",
      "DELETE FROM collector_friend WHERE collector_id NOT IN (SELECT id FROM collector);",
      "DELETE FROM collector_friend WHERE friend_id NOT IN (SELECT id FROM collector);",
      "DELETE FROM collector_identifier_archive WHERE collector_id NOT IN (SELECT id FROM collector);",
      "DELETE FROM collector_interview WHERE collector_id NOT IN (SELECT id FROM collector);",
      "DELETE FROM collector_interview WHERE collection_id NOT IN (SELECT id FROM collection);",

      "DELETE FROM interview_question WHERE collector_interview_id NOT IN (SELECT id FROM collector_interview);",
      "DELETE FROM video_collection_category WHERE collection_category_id NOT IN (SELECT id FROM collection_category);",
      "UPDATE video_collection_category SET collection_category_id = 1 WHERE collection_category_id = 0 OR collection_category_id IS NULL;",
      "DELETE FROM private_message WHERE sender NOT IN (SELECT id FROM collector);",
      "DELETE FROM comment WHERE collector_id NOT IN (SELECT id FROM collector) AND collector_id IS NOT NULL;",

      "DELETE FROM collectible_offer WHERE collectible_id NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);",
      "DELETE FROM collectible_for_sale WHERE collectible_id NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);",

      "DELETE FROM custom_value WHERE `collection_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 25) AS c WHERE collection.id = c.id);",
      "DELETE FROM custom_value WHERE `collectible_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);",

      "DELETE FROM multimedia WHERE `model` = 'Collector' AND `model_id` NOT IN (SELECT id FROM collector);",
      "DELETE FROM multimedia WHERE `model` = 'Collection' AND `model_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 25) AS c WHERE collection.id = c.id);",
      "DELETE FROM multimedia WHERE `model` = 'Collectible' AND `model_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);",

      "DELETE FROM tagging WHERE `taggable_model` = 'Collector' AND `taggable_id` NOT IN (SELECT id FROM collector);",
      "DELETE FROM tagging WHERE `taggable_model` = 'Collection' AND `taggable_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 10) AS c WHERE collection.id = c.id);",
      "DELETE FROM tagging WHERE `taggable_model` = 'Collectible' AND `taggable_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 10) AS c WHERE collectible.id = c.id);",

      "DELETE FROM term_relationship WHERE `model` = 'Collector' AND `model_id` NOT IN (SELECT id FROM collector);",
      "DELETE FROM term_relationship WHERE `model` = 'Collection' AND `model_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 10) AS c WHERE collection.id = c.id);",
      "DELETE FROM term_relationship WHERE `model` = 'Collectible' AND `model_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 10) AS c WHERE collectible.id = c.id);",

      "TRUNCATE TABLE `resource_entry`;",
      "TRUNCATE TABLE `resource_category`;",
      "TRUNCATE TABLE `sf_guard_remember_key`;"
    );

    $sqls['archive'] = array(
      "DELETE FROM collectible_archive WHERE collector_id NOT IN (". implode(',', $collector_ids) .");",
      "DELETE FROM collection_archive WHERE id NOT IN (SELECT id FROM collector_collection) AND descendant_class = 'CollectorCollection';",
      "DELETE FROM collector_identifier_archive WHERE collector_id NOT IN (". implode(',', $collector_ids) .");",
    );

    $this->log('Minimizing the export data by selectively deleting most of it.');
    $this->log('This can take a couple of minutes...');

    foreach ($sqls['propel'] as $sql)
    {
      $propel->exec($sql);
    }
    foreach ($sqls['archive'] as $sql)
    {
      $archive->exec($sql);
    }


    $stmt = $propel->prepare("
      SELECT TABLE_NAME FROM information_schema.tables
      WHERE table_schema = ?
      AND TABLE_NAME NOT LIKE '%_archive'
      AND TABLE_NAME NOT LIKE 'wp_%'
      AND TABLE_NAME NOT LIKE 'sk2_%';
    ");

    $stmt->execute(array('collectorsquest_'. $options['env']));
    while ($table = $stmt->fetch(PDO::FETCH_COLUMN))
    {
      $class = sfInflector::classify($table);
      $skip_classes = array(
        'Crontab', 'JobQueue', 'JobRun', 'CollectibleForSale',
        'PropelMigration', 'Xhprof', 'Queue', 'Message'
      );

      // Temporary skip these tables
      if (in_array($class, $skip_classes)) {
        continue;
      }

      switch ($class)
      {
        case 'Crontab':
          $class = 'iceModelCrontab';
          break;
        case 'JobQueue':
          $class = 'iceModelJobQueue';
          break;
        case 'JobRun':
          $class = 'iceModelJobRun';
          break;
        case 'Tag':
          $class = 'iceModelTag';
          break;
        case 'Tagging':
          $class = 'iceModelTagging';
          break;
        case 'SpamControl':
          $class = 'iceModelSpamControl';
          break;
      }

      $this->logSection('propel', 'Dumping table '. $table .'...');
      exec(
        sfToolkit::getPhpCli() . ' -d error_reporting=0 -d display_errors=0 ./symfony propel:data-dump'.
        ' --connection="propel" --env="'. $options['env'] .'" --classes="'. $class .'"'.
        ' > test/fixtures/common/propel/'. $table .'.yml'
      );
    }

    $stmt = $archive->prepare("
      SELECT TABLE_NAME FROM information_schema.tables
      WHERE table_schema = ?
      AND TABLE_NAME LIKE '%_archive';
    ");

    $stmt->execute(array('collectorsquest_'. $options['env']));
    while ($table = $stmt->fetch(PDO::FETCH_COLUMN))
    {
      $class = sfInflector::classify($table);
      $skip_classes = array('CollectibleForSaleArchive', 'PropelMigration', 'Queue', 'Message');

      // Temporary skip these tables
      if (in_array($class, $skip_classes)) {
        continue;
      }

      $this->logSection('archive', 'Dumping table '. $table .'...');
      exec(
        sfToolkit::getPhpCli() . ' -d error_reporting=0 -d display_errors=0 ./symfony propel:data-dump'.
        ' --connection="archive" --env="'. $options['env'] .'" --classes="'. $class .'"'.
        ' > test/fixtures/common/archive/'. $table .'.yml'
      );
    }

    $renames = array(
      'collector' => '01_collector',
      'collection_category' => '01_collection_category',
      'collection' => '02_collection',
      'collector_collection' => '03_collector_collection',
      'collectible' => '04_collectible',
      'custom_field' => '05_custom_field',
      'custom_value' => '05_custom_value',
      'sf_guard_user' => '06_sf_guard_user',
      'sf_guard_group' => '06_sf_guard_group',
    );

    foreach ($renames as $old => $new)
    {
      exec(
        'mv '.
          __DIR__ .'/../../test/fixtures/common/propel/'. $old .'.yml '.
          __DIR__ .'/../../test/fixtures/common/propel/'. $new .'.yml'
      );
    }

    $renames = array(
      'collector_archive' => '01_collector_archive',
      'collection_archive' => '02_collection_archive',
      'collectible_archive' => '03_collectible_archive'
    );

    foreach ($renames as $old => $new)
    {
      exec(
        'mv '.
          __DIR__ .'/../../test/fixtures/common/archive/'. $old .'.yml '.
          __DIR__ .'/../../test/fixtures/common/archive/'. $new .'.yml'
      );
    }
  }
}
