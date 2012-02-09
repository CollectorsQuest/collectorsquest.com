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
          'cat %s/foreign.sql %s/%s.sql | mysql -h 127.0.0.1 -u%s -p%s collectorsquest_test',
          $schema_data_dir, $schema_data_dir, $table,
          $databases['test']['propel']['param']['username'],
          $databases['test']['propel']['param']['password']
        );
      }
      else
      {
        $cmd = sprintf(
          'cat %s/foreign.sql %s/%s.sql | mysql -h 127.0.0.1 -u%s collectorsquest_test',
          $schema_data_dir, $schema_data_dir, $table,
          $databases['test']['propel']['param']['username']
        );
      }

      exec($cmd);
    }
  }
}
