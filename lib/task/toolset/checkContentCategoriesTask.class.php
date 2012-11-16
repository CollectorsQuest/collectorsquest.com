<?php

class checkContentCategoriesTask extends sfBaseTask
{
  /** @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED,
        'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED,
        'The connection name', 'propel'),
      new sfCommandOption('and-fix', null, sfCommandOption::PARAMETER_NONE,
        'Should the content category level be fixed by the task'),
    ));
    $this->namespace = 'check';
    $this->name      = 'content-categories';
    $this->briefDescription = 'Finds content categories with the wrong tree level (and fixes them)';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $con = $databaseManager->getDatabase($options['connection']
      ? $options['connection'] : null)->getConnection();

    /* @var $categories ContentCategory[] */
    $categories = ContentCategoryQuery::create()
      ->notRoot()
      ->find($con);

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

        if ($options['and-fix'])
        {
          // we will fix the category as well
          $category->setLevel($parent_level + 1);
          $category->save($con);
        }
      }
    }

    $this->log(sprintf('We have %s categories with wrong the wrong tree level', $count));

    if ($options['and-fix'])
    {
      $this->log('All wrong categories have been fixed');
    }
  }

}
