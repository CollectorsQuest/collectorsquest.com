<?php

class batchCheckMultimediaTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace = 'batch';
    $this->name      = 'check-multimedia';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    /** @var $collections Collection[] */
    $collections = CollectionQuery::create()->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
    foreach ($collections as $collection)
    {
      $this->log('Checking collection: ['. $collection->getId() .'] '. $collection->getName() .'...');

      $sizes = array('original', '150x150', '50x50');
      $multimedia = MultimediaPeer::get($collection, 'image');

      /** @var $multimedia Multimedia[] */
      foreach ($multimedia as $m)
      {
        foreach ($sizes as $size)
        if (!$m->fileExists($size))
        {
          $this->logBlock('Size '. $size .' does not exist!', 'error');
        }
      }

      $this->log('Checking collectibles...');
    }
  }
}
