<?php

class cqJobQueue extends Zend_Queue
{
  private static $_queue = array();
  private static $_databases = array();

  public static function create($name = null)
  {
    if (empty(self::$_queue[$name]))
    {
      if (empty(self::$_databases))
      {
        self::$_databases = sfYaml::load(file_get_contents(sfConfig::get('sf_config_dir').'/databases.yml'));
      }
      if (empty(self::$_databases['dev']))
      {
        self::$_databases['dev']  = self::$_databases['all'];
      }

      $env = sfConfig::get('sf_environment') == 'prod' ? 'all' : sfConfig::get('sf_environment');

      $options = array(
        'name'          => $name,
        'driverOptions' => array(
          'host'      => 'master.cwvkronpbfrx.us-east-1.rds.amazonaws.com',
          'port'      => '3306',
          'username'  => self::$_databases[$env]['queue']['param']['username'],
          'password'  => self::$_databases[$env]['queue']['param']['password'],
          'dbname'    => 'collectorsquest_queue',
          'type'      => 'pdo_mysql'
        )
      );

      cqStatic::loadZendFramework();

      $adapter = new Zend_Queue_Adapter_Db($options);
      self::$_queue[$name] = new cqJobQueue($adapter, $options);
    }

    return self::$_queue[$name];
  }
}
