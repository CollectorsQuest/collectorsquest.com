<?php

class batchProcessJobQueueTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'legacy'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('queue', null, sfCommandOption::PARAMETER_REQUIRED, 'Which queue to process', 'all')
    ));

    $this->namespace        = 'batch';
    $this->name             = 'process-job-queue';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [batch:process-job-queue|INFO] task reads the current job queue and executes the events for each message

Call it with:

  [php symfony batch:process-job-queue|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // Let's load Zend Framework
    cqStatic::loadZendFramework();

    $queues = ($options['queue'] != 'all') ? array($options['queue']) : cqJobQueue::create()->getQueues();

    // Get list of queues
    foreach ($queues as $name)
    {
      $queue = cqJobQueue::create($name);

      switch ($name)
      {
        case 'multimedia_thumbs':
          $messages = $queue->receive(15);
          foreach ($messages as $message)
          {
            list($pk, $size, $method) = explode(', ', $message->body);

            if ($multimedia = MultimediaPeer::retrieveByPK($pk))
            {
              try {
                $multimedia->makeThumb($size, $method, false);
              }
              catch (Exception $e) { ; }
            }

            $queue->deleteMessage($message);
          }
          break;
        case 'multimedia_colors':
          $messages = $queue->receive(10);
          foreach ($messages as $message)
          {
            if (($multimedia = MultimediaPeer::retrieveByPK((int) $message->body)) && !$multimedia->getColors())
            {
              try {
                $colors = MultimediaPeer::getImageColors($multimedia->getAbsolutePath('original'));

                $multimedia->setColors($colors);
                $multimedia->save();
              }
              catch (Exception $e) { ; }
            }

            $queue->deleteMessage($message);
          }
          break;
        case 'multimedia_rotate':
          $messages = $queue->receive(15);
          foreach ($messages as $message)
          {
            list($pk, $size, $degrees) = explode(', ', $message->body);

            if ($multimedia = MultimediaPeer::retrieveByPK($pk))
            {
              try {
                $multimedia->rotate($size, $degrees, false);
              }
              catch (Exception $e) { ; }
            }

            $queue->deleteMessage($message);
          }
          break;
      }
    }
  }
}
