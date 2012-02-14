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
}
