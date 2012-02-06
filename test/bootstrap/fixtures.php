<?php

new sfDatabaseManager($configuration);

// Set the default culture for the tests
sfPropel::setDefaultCulture('en');

// Get the Propel connection and temporarily turn off foreign key checks
$connection = Propel::getConnection('propel');
$connection->exec('SET FOREIGN_KEY_CHECKS = 0;');

$loader = new cqPropelData();

/**
 * If we do do not TRUNCATE TABLE, the primary keys are not started from 0 on each test
 * so instead of creating a new class from sfPropelData, we do the truncating here
 */
$files = $loader->getFiles(sfConfig::get('sf_test_dir').'/fixtures/common');
foreach ($files as $file)
{
  $data = sfYaml::load($file);

  if ($data === null)
  {
    // no data
    continue;
  }

  $classes = array_keys($data);
  foreach (array_reverse($classes) as $class)
  {
    $class = trim($class);

    // Check that peer class exists before calling doDeleteAll()
    if (class_exists(constant($class.'::PEER')))
    {
      $table = constant(constant($class.'::PEER')."::TABLE_NAME");
      $connection->exec('TRUNCATE TABLE '. $table);
    }
  }
}

// Run all the init SQL queries against the test database
$queries = file(__DIR__.'/../../data/sql/lib.model.init.sql');
foreach ($queries as $query)
{
  if (($query = trim($query)) && stripos($query, 'SET FOREIGN_KEY_CHECKS') === false && substr($query, 0, 1) != '-')
  {
    $connection->exec($query);
  }
}

// Load the fixtures
$loader->loadData(array(sfConfig::get('sf_test_dir').'/fixtures/common'));

// Set the foreign key checks to 1
$connection->exec('SET FOREIGN_KEY_CHECKS = 1;');
