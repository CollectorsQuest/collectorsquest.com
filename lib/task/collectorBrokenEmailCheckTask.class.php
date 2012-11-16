<?php

/**
 * A simple check task for collector emails
 */
class collectorBrokenEmailCheckTask extends sfBaseTask
{
  /** @var sfProjectConfiguration */
  protected $configuration = null;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'collector';
    $this->name      = 'broken-email-check';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    /* @var $propel PropelPDO */
    $propel = $databaseManager->getDatabase('propel')->getConnection();

    /* @var $collectors PropelObjectCollection|Collector[] */
    $collectors = CollectorQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($propel);

    $v = new sfValidatorEmail();

    $count = 0;
    foreach ($collectors as $collector)
    {
      try
      {
        $v->clean($collector->getEmail());
      }
      catch (sfValidatorError $e)
      {
        $this->log(sprintf(
          'ID: %d  UserName: %s  Email: %s',
          $collector->getId(), $collector->getUsername(), $collector->getEmail())
        );
        $count++;
      }
    }

    $this->log('Total: '.$count);
  }

}
