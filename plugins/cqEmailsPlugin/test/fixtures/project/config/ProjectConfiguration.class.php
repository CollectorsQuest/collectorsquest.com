<?php

if (!isset($_SERVER['SYMFONY']))
{
  throw new RuntimeException('Could not find symfony core libraries.');
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();


class ProjectConfiguration extends sfProjectConfiguration
{

  public function setup()
  {
    $this->setPlugins(array('cqEmailsPlugin'));
    $this->setPluginPath('cqEmailsPlugin', dirname(__FILE__).'/../../../..');
    $this->setPluginPath('sfPropelPlugin', $_SERVER['SYMFONY'].'/plugins/sfPropelPlugin');
  }


  public function initializePropel($app)
  {
    // build Propel om/map/sql/forms
    $files = glob(sfConfig::get('sf_lib_dir').'/model/om/*.php');
    if (false === $files || !count($files))
    {
      chdir(sfConfig::get('sf_root_dir'));
      $task = new sfPropelBuildModelTask($this->dispatcher, new sfFormatter());
      ob_start();
      $task->run();
      $output = ob_get_clean();
    }

    $files = glob(sfConfig::get('sf_data_dir').'/sql/*.sql');
    if (false === $files || !count($files))
    {
      chdir(sfConfig::get('sf_root_dir'));
      $task = new sfPropelBuildSqlTask($this->dispatcher, new sfFormatter());
      ob_start();
      $task->run();
      $output = ob_get_clean();
    }

    /* * /
    $files = glob(sfConfig::get('sf_lib_dir').'/form/base/*.php');
    if (false === $files || !count($files))
    {
      chdir(sfConfig::get('sf_root_dir'));
      $task = new sfPropelBuildFormsTask($this->dispatcher, new sfFormatter());
      $task->run(array(), array('application='.$app));
    }
    /* */
  }


  public function loadFixtures($fixtures)
  {
    // initialize database manager
    $databaseManager = new sfDatabaseManager($this);

    // cleanup database
    $db = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'/database.sqlite';
    if (file_exists($db))
    {
      unlink($db);
    }

    // initialize database
    $sql = file_get_contents(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'lib.model.schema.sql');
    //$sql .= PHP_EOL . file_get_contents(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'plugins.ncPropelChangeLogBehaviorPlugin.lib.model.schema.sql');
    $sql = preg_replace('/^\s*\-\-.+$/m', '', $sql);
    $sql = preg_replace('/^\s*DROP TABLE .+?$/m', '', $sql);
    $con = Propel::getConnection();
    $tables = preg_split('/CREATE TABLE/', $sql);
    foreach ($tables as $table)
    {
      $table = trim($table);
      if (!$table)
      {
        continue;
      }

      $con->query('CREATE TABLE '.$table);
    }

    // load fixtures
    $data = new sfPropelData();
    if (is_array($fixtures))
    {
      $data->loadDataFromArray($fixtures);
    }
    else
    {
      $data->loadData(sfConfig::get('sf_data_dir').'/'.$fixtures);
    }
  }

}
