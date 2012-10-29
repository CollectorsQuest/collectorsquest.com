<?php

class testContentCategoriesTask extends sfBaseTask
{
  /** @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    unset($_SERVER['PATH_TRANSLATED'], $_SERVER['SCRIPT_NAME']);

    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');

    $this->namespace = 'test';
    $this->name      = 'content-categories';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $_SERVER['HTTP_HOST'] = sfConfig::get('app_www_domain');

    sfContext::createInstance($this->configuration);

    // Database initialization
    new sfDatabaseManager($this->configuration);

    // For the sitemap we can easily use the slave servers
    Propel::setForceMasterConnection(false);

    /**
     * Get a read-only connection
     *
     * @var $connection PropelPDO
     */
    $connection = Propel::getConnection('propel', Propel::CONNECTION_READ);

    /* @var $q ContentCategoryQuery */
    $q = ContentCategoryQuery::create()
      ->notRoot();

    $categories = $q->find($connection);

    // the number of categories with problems
    $count = 0;

    foreach ($categories as $category)
    {
      /* @var $category ContentCategory */
      $level = $category->getLevel();

      /* @var $parent ContentCategory */
      $parent = $category->getParent();
      $parent_level = $parent->getLevel();


      if (($level - 1) != $parent_level)
      {
        $this->log(
          $category . ' ID: ' .$category->getId().  ' level: ' . $level .
          ' parent: '. $parent . ' parent level: ' . $parent_level
        );

        $count++;
      }
    }

    $this->log(sprintf('We have %s categories with wrong level', $count));
  }
}
