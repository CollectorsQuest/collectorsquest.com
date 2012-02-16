<?php

class cqTest
{
  /**
   * Reset the specified tables to their original content from the .sql files
   *
   * @param array $tables
   */
  static public function resetTables($tables)
  {
    $schema_data_dir = sfConfig::get('sf_test_dir') . '/schemas';
    $databases = sfYaml::load(file_get_contents(sfConfig::get('sf_config_dir') . '/databases.yml'));

    if (is_array($tables))
    foreach ($tables as $table)
    {
      if (!is_file($schema_data_dir . '/' . $table . '.sql'))
      {
        continue;
      }

      if (!empty($databases['test']['propel']['param']['password']))
      {
        $cmd = sprintf(
          'cat %s/foreign.sql %s/%s.sql | mysql -h 172.16.183.128 -u%s -p%s collectorsquest_test',
          $schema_data_dir, $schema_data_dir, $table,
          $databases['test']['propel']['param']['username'],
          $databases['test']['propel']['param']['password']
        );
      }
      else
      {
        $cmd = sprintf(
          'cat %s/foreign.sql %s/%s.sql | mysql -h 172.16.183.128 -u%s collectorsquest_test',
          $schema_data_dir, $schema_data_dir, $table,
          $databases['test']['propel']['param']['username']
        );
      }

      exec($cmd);
    }
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
    return null;
  }

  /**
   * Load model fixtures from test/fixtures
   *
   * Example usage:
   * cqTest::loadFixtureDirs(array('legacy/01_first', 'all/02_second')
   *
   * Will load files matching:
   *  - /test/fixtures/legacy/01_first/*.yml
   *  - /test/fixtures/all/02_second/*.yml
   *
   * @param     string|array $dirs You can pass a string for a single file, or an array
   * @param     PropelPDO $con
   */
  public static function loadFixtureDirs($dirs, PropelPDO $con = null)
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

    if (is_null($con))
    {
      $con = Propel::getConnection();
    }

    $con->prepare('SET FOREIGN_KEY_CHECKS = 0;')->execute();

    // load fixtures; this cleans the database too
    $loader = new sfPropelData();
    $loader->loadData($dirs);

    $con->prepare('SET FOREIGN_KEY_CHECKS = 1;')->execute();
  }

}
