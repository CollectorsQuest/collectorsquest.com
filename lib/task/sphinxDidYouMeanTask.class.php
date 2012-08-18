<?php

class sphinxDidYouMeanTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev')
    ));

    $this->namespace        = 'sphinx';
    $this->name             = 'did-you-mean';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [sphinx:did-you-mean|INFO] task rebuilds the "Did you mean?" MySQl table.
Call it with:

  [php symfony sphinx:did-you-mean|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    /** @var $connection PropelPDO */
    $connection = Propel::getConnection('propel', Propel::CONNECTION_READ);

    $connection->exec('TRUNCATE TABLE search_did_you_mean');
    $stmt = $connection->prepare(
      'INSERT INTO `search_did_you_mean` VALUES (0, :keyword, :trigrams, :freq)
       ON DUPLICATE KEY UPDATE `freq` = `freq` + :freq;'
    );

    $indexes = array();
    $indexes[] = sprintf('%s_blog_normalized', $options['env']);
    $indexes[] = sprintf('%s_collectors_normalized', $options['env']);
    $indexes[] = sprintf('%s_collections_normalized', $options['env']);
    $indexes[] = sprintf('%s_collectibles_normalized', $options['env']);

    foreach ($indexes as $index)
    {
      $cmd = sprintf(
       '/usr/bin/indexer %s --config /www/etc/sphinx.running.conf \
        --buildstops /www/tmp/stopwords.txt 100000 --buildfreqs',
       $index
      );
      exec($cmd);

      $in = fopen('/www/tmp/stopwords.txt', 'r');
      while ($line = fgets($in, 1024))
      {
        list($keyword, $freq) = explode(' ', trim($line));

        if ($freq < 40 || strstr($keyword, '_') !== false || strstr($keyword, "'") !== false)
        {
          continue;
        }

        $t = '__' . $keyword . '__';

        $trigrams = '';
        for ($i = 0; $i < strlen($t) - 2; $i++)
        {
          $trigrams .= substr($t, $i, 3) . ' ';
        }

        $stmt->execute(array(
            'keyword' => $keyword,
            'trigrams' => $trigrams,
            'freq' => $freq
        ));
      }
    }

    // Cleanup
    unlink('/www/tmp/stopwords.txt');

    return 0;
  }
}
