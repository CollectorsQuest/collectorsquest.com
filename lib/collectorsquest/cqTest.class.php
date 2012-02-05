<?php

class cqTest
{
  /**
   * Reset the specified tables to their original content from the .sql files
   */
  static public function resetTables($tables)
  {
    $schema_data_dir = sfConfig::get('sf_data_dir').'/test/sql/';
    $databases = sfYaml::load(file_get_contents(sfConfig::get('sf_config_dir').'/databases.yml'));

    if (is_array($tables))
    foreach ($tables as $table)
    {
      if (!is_file($schema_data_dir.'/'.$table.'.sql'))
      {
        continue;
      }

      if (!empty($databases['test']['propel']['param']['password']))
      {
        $cmd = sprintf(
          'mysql -h 127.0.0.1 -u%s -p%s collectorsquest_test < %s/%s.sql',
          $databases['test']['propel']['param']['username'],
          $databases['test']['propel']['param']['password'],
          $schema_data_dir, $table
        );
      }
      else
      {
        $cmd = sprintf(
          'mysql -h 127.0.0.1 -u%s collectorsquest_test < %s/%s.sql',
          $databases['test']['propel']['param']['username'],
          $schema_data_dir, $table
        );
      }

      exec($cmd);
    }
  }
}
