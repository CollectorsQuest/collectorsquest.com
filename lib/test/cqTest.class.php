<?php

class cqTest
{
  /**
   * Reset the specified classes to their original content from the .sql files.
   * This is shortcut to self::resetTables with all tables which have to
   * do with the specified class(es)
   *
   * @param array $classes
   */
  static public function resetClasses($classes)
  {
    foreach ((array) $classes as $class)
    switch (sfInflector::classify($class))
    {
      case 'Collector':
        self::resetTables(array(
          'collector',
          'collector_archive',
          'collector_collection',
          'collector_email',
          'collector_extra_property',
          'collector_friend',
          'collector_geocache',
          'collector_geocache_archive',
          'collector_identifier',
          'collector_identifier_archive',
          'collector_interview',
          'collector_profile',
          'collector_profile_archive',
          'collector_profile_extra_property',
        ));
        break;

      case 'Collection':
        self::resetTables(array(
          'collection',
          'collection_archive',
          'collection_category',
          'collection_category_field',
          'collection_collectible',
          'collector_collection',
        ));
        break;

      case 'Collectible':
        self::resetTables(array(
          'collector',
          'collection',
          'collectible',
          'collection_collectible',
          'collectible_archive',
          'collectible_for_sale',
          'collectible_for_sale_archive',
        ));
        break;
      case 'Organization':
        self::resetTables(array(
          'organization',
          'organization_type',
          'organization_membership',
          'organization_membership_request',
        ));
        break;

      default:
        self::resetTables(sfInflector::tableize($class));
        break;
    }
  }

  /**
   * Reset the specified tables to their original content from the .sql files
   *
   * @param array $tables
   */
  static public function resetTables($tables)
  {
    $schema_data_dir = sfConfig::get('sf_test_dir') . '/schemas';
    $databases = sfYaml::load(file_get_contents(sfConfig::get('sf_config_dir') . '/databases.yml'));

    // Initiate the $files array with only foreign.sql
    $files = array($schema_data_dir . '/foreign.sql');

    foreach ((array) $tables as $table)
    if (is_file($schema_data_dir . '/' . $table . '.sql'))
    {
      $files[] = $schema_data_dir . '/' . $table . '.sql';
    }

    /**
     * The command bellow outputs one line per file like:
     *
     * [code]
     *   source /mnt/vhosts/collectorsquest.com/test/schemas/foreign.sql
     *   source /mnt/vhosts/collectorsquest.com/test/schemas/collector.sql
     *   source /mnt/vhosts/collectorsquest.com/test/schemas/collection.sql
     *   source /mnt/vhosts/collectorsquest.com/test/schemas/collectible.sql
     * [/code]
     *
     * which is then piped to "mysql --batch --raw" for maximum performance
     */
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $cmd = "cat ". implode(' ', $files);
    } else {
      $cmd = "find ". implode(' ', $files) ." -name '*.sql' | awk '{ print \"source\", $0 }'";
    }

    if (!empty($databases['test']['propel']['param']['password']))
    {
      $cmd .= sprintf(
        " | mysql --batch --raw -h 172.16.183.128 -u%s -p%s -D collectorsquest_test",
        $databases['test']['propel']['param']['username'],
        $databases['test']['propel']['param']['password']
      );
    }
    else
    {
      $cmd .= sprintf(
        " | mysql --batch --raw -h 172.16.183.128 -u%s -D collectorsquest_test",
        $databases['test']['propel']['param']['username']
      );
    }

    exec($cmd);
  }

  /**
   * Load model fixtures from test/fixtures
   *
   * Example usage:
   * cqTest::loadFixtureDirs(array(
   *  'legacy/01_first/01_something.yml',
   *  'legacy/02_second',
   *  'all/03_third'
   * )
   *
   * Will load files matching:
   *  - /test/fixtures/legacy/01_first/01_something.yml
   *  - /test/fixtures/legacy/02_second/*.yml
   *  - /test/fixtures/all/03_third/*.yml
   *
   * @param     string|array $dirs You can pass a string for a single file, or an array
   * @param     boolean $delete_current_data Append or delete data?
   * @param     PropelPDO $con
   */
  public static function loadFixtures($dirs, $delete_current_data = false, PropelPDO $con = null)
  {
    if (is_array($dirs))
    {
      foreach ($dirs as $key => $dir)
      {
        $dirs[$key] = sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $dir;
      }
    }
    elseif (is_string($dirs))
    {
      $dirs = sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $dirs;
    }
    else
    {
      throw new InvalidArgumentException(sprintf('[Model Unit Test] cqTest::loadFixtureDirs() requires the $dirs parameter to be either array or a string'));
    }

    if (null === $con)
    {
      $con = Propel::getConnection();
    }

    $con->prepare('SET FOREIGN_KEY_CHECKS = 0;')->execute();

    // load fixtures; this cleans the database too
    $loader = new cqPropelData();
    $loader->setDeleteCurrentData($delete_current_data);
    $loader->loadData($dirs);

    $con->prepare('SET FOREIGN_KEY_CHECKS = 1;')->execute();
  }


  /**
   * @static
   *
   * @param  string   $model
   * @param  boolean  $random
   *
   * @return BaseObject
   */
  public static function getModelObject($model, $random = true)
  {
    $class = sfInflector::classify($model);

    /** @var $q ModelCriteria */
    if ($q = call_user_func(array($class . 'Query', 'create')))
    {
      if ($random === true)
      {
        $q->addAscendingOrderByColumn('RAND()');
      }

      return $q->findOne();
    }

    return null;
  }

  /**
   * @static
   *
   * @param  string   $model The name of the model we want a new instance of
   * @param  boolean  $save Whether to save the Model before returning it
   *
   * @return BaseObject
   */
  public static function getNewModelObject($model, $save = true)
  {
    $object = self::getFakeModelObject($model);

    if ($save === true && method_exists($object, 'save'))
    {
      $object->save();
    }

    return $object;
  }

  /**
   * @static
   *
   * @param  string  $model
   * @return BaseObject
   */
  private static function getFakeModelObject($model)
  {
    /** @todo: Implement the real Faking */
    return self::getModelObject($model, true);
  }

  /**
   * @static
   *
   * @param string $username
   * @return bool|int
   */
  public static function allowCqnext($username)
  {
    $collector = CollectorPeer::retrieveByUsername($username);

    if (!$collector)
    {
      return false;
    }

    $collector->setCqnextAccessAllowed(true);

    return $collector->save();
  }
}
