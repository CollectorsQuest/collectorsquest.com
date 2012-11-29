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

    $collector_ids = array(
      163, 963, 644, 59, 4208, 6700, 1212, 4295, 846, 9367
    );

    /** @var $sqls array */
    $sqls = array();

    $sqls['propel'] = array(
      'SET FOREIGN_KEY_CHECKS = 0;',

      'TRUNCATE TABLE collector_identifier;',
      'TRUNCATE TABLE `shopping_payment_extra_property`;',
      'TRUNCATE TABLE `shopping_payment`;',
      'TRUNCATE TABLE `shopping_order`;',
      'TRUNCATE TABLE `shopping_cart_collectible`;',
      'TRUNCATE TABLE `shopping_cart`;',

      'DELETE FROM collectible WHERE collector_id NOT IN ('.  implode(',', $collector_ids) .');',
      'DELETE FROM collectible_rating WHERE collectible_id NOT IN (SELECT id FROM collectible);',
      'DELETE FROM collection_collectible WHERE collectible_id NOT IN (SELECT id FROM collectible)',
      'DELETE FROM collector_collection WHERE collector_id NOT IN ('.  implode(',', $collector_ids) .');',
      'DELETE FROM collector_collection_rating WHERE collector_collection_id NOT IN (SELECT id FROM collector_collection)',
      "DELETE FROM collection WHERE id NOT IN (SELECT id FROM collector_collection) AND descendant_class = 'CollectorCollection';",
      'DELETE FROM collection_rating WHERE collection_id NOT IN (SELECT id FROM collection)',
      'DELETE FROM collector_email WHERE collector_id NOT IN ('.  implode(',', $collector_ids) .');',
      'DELETE FROM collector WHERE id NOT IN ('.  implode(',', $collector_ids) .');',
      'DELETE FROM collector_rating WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_profile WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_profile_extra_property WHERE collector_profile_collector_id NOT IN (SELECT collector_id FROM collector_profile);',
      'DELETE FROM collector_extra_property WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_address WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_geocache WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_remember_key WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_friend WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_friend WHERE friend_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_interview WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM collector_interview WHERE collection_id NOT IN (SELECT id FROM collection);',
      'DELETE FROM private_message WHERE sender NOT IN (SELECT id FROM collector);',
      'DELETE FROM private_message WHERE receiver NOT IN (SELECT id FROM collector);',
      'DELETE FROM private_message_extra_property WHERE private_message_id NOT IN (SELECT id FROM private_message);',

      'DELETE FROM interview_question WHERE collector_interview_id NOT IN (SELECT id FROM collector_interview);',
      'DELETE FROM video_collection_category WHERE collection_category_id NOT IN (SELECT id FROM collection_category);',
      'UPDATE video_collection_category SET collection_category_id = 1 WHERE collection_category_id = 0 OR collection_category_id IS NULL;',
      'DELETE FROM comment WHERE collector_id NOT IN (SELECT id FROM collector) AND collector_id IS NOT NULL;',
      'DELETE FROM comment WHERE collection_id NOT IN (SELECT id FROM collection) AND collection_id IS NOT NULL;',
      'DELETE FROM comment WHERE collectible_id NOT IN (SELECT id FROM collectible) AND collectible_id IS NOT NULL;',

      'DELETE FROM collectible_for_sale WHERE collectible_id NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);',

      'DELETE FROM custom_value WHERE `collection_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 25) AS c WHERE collection.id = c.id);',
      'DELETE FROM custom_value WHERE `collectible_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);',

      "DELETE FROM multimedia WHERE `model` = 'Collector' AND `model_id` NOT IN (SELECT id FROM collector);",
      "DELETE FROM multimedia WHERE `model` = 'Collection' AND `model_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 25) AS c WHERE collection.id = c.id);",
      "DELETE FROM multimedia WHERE `model` = 'Collectible' AND `model_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 25) AS c WHERE collectible.id = c.id);",

      "DELETE FROM tagging WHERE `taggable_model` = 'Collector' AND `taggable_id` NOT IN (SELECT id FROM collector);",
      "DELETE FROM tagging WHERE `taggable_model` = 'Collection' AND `taggable_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 10) AS c WHERE collection.id = c.id);",
      "DELETE FROM tagging WHERE `taggable_model` = 'Collectible' AND `taggable_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 10) AS c WHERE collectible.id = c.id);",
      "DELETE FROM tag WHERE `id` NOT IN (SELECT tagging.tag_id FROM tagging);",

      "DELETE FROM term_relationship WHERE `model` = 'Collector' AND `model_id` NOT IN (SELECT id FROM collector);",
      "DELETE FROM term_relationship WHERE `model` = 'Collection' AND `model_id` NOT IN (SELECT collection.id FROM collection JOIN (SELECT collection.id FROM collection ORDER BY RAND() LIMIT 10) AS c WHERE collection.id = c.id);",
      "DELETE FROM term_relationship WHERE `model` = 'Collectible' AND `model_id` NOT IN (SELECT collectible.id FROM collectible JOIN (SELECT collectible.id FROM collectible ORDER BY RAND() LIMIT 10) AS c WHERE collectible.id = c.id);",

      'DELETE FROM package_transaction WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM package_transaction_credit WHERE package_transaction_id NOT IN (SELECT id FROM package_transaction);',
      'DELETE FROM promotion_transaction WHERE collector_id NOT IN (SELECT id FROM collector);',

      'DELETE FROM shopping_order WHERE collector_id NOT IN (SELECT id FROM collector);',
      'DELETE FROM shopping_cart WHERE collector_id NOT IN (SELECT id FROM collector);',

      'UPDATE collector SET eblob = NULL WHERE 1',
      'UPDATE collector_archive SET eblob = NULL WHERE 1',
      'UPDATE collector_collection SET eblob = NULL WHERE 1',
      'UPDATE collector_collection_archive SET eblob = NULL WHERE 1',
      'UPDATE collection SET eblob = NULL WHERE 1',
      'UPDATE collection_archive SET eblob = NULL WHERE 1',
      'UPDATE collectible SET eblob = NULL WHERE 1',
      'UPDATE collectible_archive SET eblob = NULL WHERE 1',

      'TRUNCATE TABLE `resource_entry`;',
      'TRUNCATE TABLE `resource_category`;',
      'TRUNCATE TABLE `sf_guard_remember_key`;',

      'SET FOREIGN_KEY_CHECKS = 1;',
    );

    $sqls['archive'] = array(
      'SET FOREIGN_KEY_CHECKS = 0;',
      'DELETE FROM collectible_archive WHERE collector_id NOT IN ('.  implode(',', $collector_ids) .');' ,
      "DELETE FROM collection_archive
        WHERE id NOT IN (SELECT id FROM collector_collection)
          AND descendant_class = 'CollectorCollection';",
      'DELETE FROM collector_identifier_archive WHERE collector_id NOT IN ('.  implode(',', $collector_ids) .');' ,
      'SET FOREIGN_KEY_CHECKS = 1;'
    );

    $wp_post_ids = array(
      3621, 14559, 14570, 14597, 14621, 14629, 14658, 14680, 14689, 14704,
      14633, 14725, 14722, 14733, 14742, 14763, 14822, 14819, 14897, 14901
    );

    $stmt = $propel->prepare("
      SELECT ID FROM `wp_posts`
       WHERE `post_type` IN ('cms_slot', 'homepage_carousel', 'homepage_showcase', 'collectors_question')
    ");
    $stmt->execute(array('collectorsquest_'. $options['env']));
    while ($ID = $stmt->fetch(PDO::FETCH_COLUMN))
    {
      $wp_post_ids[] = (int) $ID;
    }

    $sqls['blog'] = array(
      'SET FOREIGN_KEY_CHECKS = 0;',
      'DROP TABLE IF EXISTS `wp_bad_behavior`;',
      'DROP TABLE IF EXISTS `wp_postrank`;',
      'DROP TABLE IF EXISTS `wp_sk2_spams`;',
      'DROP TABLE IF EXISTS `wp_sk2_logs`;',
      'DROP TABLE IF EXISTS `wp_sph_counter`;',
      'DROP TABLE IF EXISTS `wp_sph_stats`;',
      'TRUNCATE TABLE `wp_links`;',
      'TRUNCATE TABLE `wp_comments`;',
      'TRUNCATE TABLE `wp_commentmeta`;',
      'DELETE FROM `wp_options` WHERE `option_id` > 50;',
      "DELETE FROM `wp_posts` WHERE `ID` NOT IN (".  implode(',', $wp_post_ids) .") AND `post_type` <> 'attachment';",
      'DELETE FROM `wp_posts` WHERE `post_parent` <> 0 AND `post_parent` NOT IN ('.  implode(',', $wp_post_ids) .');' ,
      'DELETE FROM `wp_postmeta` WHERE `post_id` NOT IN (SELECT ID FROM `wp_posts`);',
      'DELETE FROM `wp_term_relationships` WHERE `object_id` NOT IN (SELECT ID FROM `wp_posts`);',
      'DELETE FROM `wp_term_taxonomy` WHERE `term_taxonomy_id` NOT IN (SELECT `term_taxonomy_id` FROM `wp_term_relationships`);',
      'DELETE FROM `wp_terms` WHERE `term_id` NOT IN (SELECT `term_id` FROM `wp_term_taxonomy`);',
      'SET FOREIGN_KEY_CHECKS = 1;'
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
    foreach ($sqls['blog'] as $sql)
    {
      $archive->exec($sql);
    }

    /**
     * Propel
     */
    $stmt = $propel->prepare("
      SELECT TABLE_NAME FROM information_schema.tables
      WHERE table_schema = ?
      AND TABLE_NAME NOT LIKE '%_archive'
      AND TABLE_NAME NOT LIKE 'search_%'
      AND TABLE_NAME NOT LIKE 'wp_%'
      AND TABLE_NAME NOT LIKE 'wp_wpfaq%'
      AND TABLE_NAME NOT LIKE 'wp_sk2_%'
      AND TABLE_NAME NOT LIKE 'sk2_%';
    ");

    $stmt->execute(array('collectorsquest_'. $options['env']));
    while ($table = $stmt->fetch(PDO::FETCH_COLUMN))
    {
      $class = sfInflector::classify($table);
      $skip_classes = array(
        'Crontab', 'JobQueue', 'JobRun', 'CollectibleForSale',
        'PropelMigration', 'Xhprof', 'Queue', 'Message', 'iceModelGeoCountry'
      );

      // Temporary skip these tables
      if (in_array($class, $skip_classes))
      {
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
        case 'MetaTag':
          $class = 'iceModelMetaTag';
          break;
        case 'MetaTagI18n':
          $class = 'iceModelMetaTagI18n';
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

    /**
     * Archive
     */
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
      if (in_array($class, $skip_classes))
      {
        continue;
      }

      // Exception for multimedia_archive table
      $connection = ($class == 'MultimediaArchive') ? 'propel' : 'archive';

      $this->logSection('archive', 'Dumping table '. $table .'...');
      exec(
        sfToolkit::getPhpCli() . ' -d error_reporting=0 -d display_errors=0 ./symfony propel:data-dump'.
        ' --connection="'. $connection .'" --env="'. $options['env'] .'" --classes="'. $class .'"'.
        ' > test/fixtures/common/archive/'. $table .'.yml'
      );
    }

    /**
     * Blog
     */
    $stmt = $archive->prepare("
      SELECT TABLE_NAME FROM information_schema.tables
      WHERE table_schema = ?
      AND TABLE_NAME LIKE 'wp_%'
      AND TABLE_NAME NOT LIKE 'wp_pbci%'
      AND TABLE_NAME NOT LIKE 'wp_wpfaq%'
      AND TABLE_NAME NOT LIKE 'wp_sk2_%';
    ");

    $stmt->execute(array('collectorsquest_'. $options['env']));
    while ($table = $stmt->fetch(PDO::FETCH_COLUMN))
    {
      $class = sfInflector::classify(rtrim($table, 's'));

      $this->logSection('blog', 'Dumping table '. $table .'...');
      exec(
        sfToolkit::getPhpCli() . ' -d error_reporting=0 -d display_errors=0 ./symfony propel:data-dump'.
          ' --connection="blog" --env="'. $options['env'] .'" --classes="'. $class .'"'.
          '| sed "s/'. ucfirst($class) .'/'. lcfirst($class) .'/g" > test/fixtures/common/blog/'. $table .'.yml'
      );
    }

    /**
     * Rename fixtures for import order
     */
    $renames = array(
      'collector' => '01_collector',
      'collection_category' => '01_collection_category',
      'content_category' => '01_content_category',
      'collection' => '02_collection',
      'collector_collection' => '03_collector_collection',
      'collectible' => '04_collectible',
      'custom_field' => '05_custom_field',
      'custom_value' => '05_custom_value',
      'sf_guard_user' => '06_sf_guard_user',
      'sf_guard_group' => '06_sf_guard_group',
      'promotion' => '08_promotion',
      'promotion_transaction' => '08_promotion_transaction',
      'package' => '09_package',
      'package_transaction' => '09_package_transaction',
      'package_transaction_credit' => '09_package_transaction_credit',
      'shipping_reference' => '10_shipping_reference',
      'shipping_rate' => '11_shipping_rate'
    );

    foreach ($renames as $old => $new)
    {
      exec(
        'mv '.
          __DIR__ .'/../../test/fixtures/common/propel/'. $old .'.yml '.
          __DIR__ .'/../../test/fixtures/common/propel/'. $new .'.yml'
      );
    }

    /**
     * Rename fixtures for import order
     */
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

    /**
     * Rename fixtures for import order
     */
    $renames = array(
      'wp_users' => '01_wp_users',
      'wp_terms' => '02_wp_terms',
      'wp_term_taxonomy' => '03_wp_term_taxonomy',
      'wp_posts' => '04_wp_posts'
    );

    foreach ($renames as $old => $new)
    {
      exec(
        'mv '.
          __DIR__ .'/../../test/fixtures/common/blog/'. $old .'.yml '.
          __DIR__ .'/../../test/fixtures/common/blog/'. $new .'.yml'
      );
    }
  }
}
