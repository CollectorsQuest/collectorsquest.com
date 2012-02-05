<?php

class sphinxRebuildIndexesTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('indexes', sfCommandArgument::OPTIONAL, 'Sphinx indexes to rebuild'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('delta', null, sfCommandOption::PARAMETER_OPTIONAL, 'Whether to build the deltas only', 'no')
    ));

    $this->namespace        = 'sphinx';
    $this->name             = 'rebuild-indexes';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [sphinx:rebuild-indexes|INFO] task rebuilds all Autohop sphinx indexes or only the ones specified.
Call it with:

  [php symfony sphinx:rebuild-indexes|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // We need to run this task with root or sphinx users
    if (!in_array(get_current_user(), array('', 'root', 'sphinx', 'vagrant')))
    {
      $this->logBlock('You must run this task with root or sphinx priviliges!', 'error');
      return false;
    }

    $indexes = array();

    if (empty($arguments["indexes"]))
    {
      if ($options['delta'] == 'yes')
      {
        $indexes[] = sprintf('%s_blog_delta', $options['env']);
      }
      else
      {
        $indexes[] = sprintf('%s_collections', $options['env']);
        $indexes[] = sprintf('%s_collectors', $options['env']);
        $indexes[] = sprintf('%s_collectibles', $options['env']);
        $indexes[] = sprintf('%s_blog', $options['env']);
      }
    }
    else
    {
      $indexes = explode(',', $arguments["indexes"]);
    }

    if (!empty($indexes))
    {
      // Create a temporary Sphinx config file
      $conf = tempnam('/www/tmp', 'sphinx_config_');

      $files = sfFinder::type('file')
             ->sort_by_name()
             ->follow_link()
             ->name('/(.*)\.conf$/')
             ->maxdepth(1)
             ->in(sfConfig::get('sf_config_dir').'/sphinx/');

      foreach ($files as $file)
      {
        file_put_contents($conf, file_get_contents($file), FILE_APPEND);
      }

      // Add the main configuration file
      file_put_contents($conf, file_get_contents('/www/etc/sphinx/sphinx.conf'), FILE_APPEND);
      $cmd = sprintf('/usr/bin/indexer --rotate --config %s %s', $conf, implode(' ', $indexes));

      if (!get_current_user() || get_current_user() == 'root' || get_current_user() == 'vagrant')
      {
        chown($conf, 'sphinx');
        $cmd = 'sudo -u sphinx '. $cmd;
      }

      // Run the command
      passthru($cmd);

      // Remove the temp config file
      unlink($conf);
    }

    return 0;
  }
}
