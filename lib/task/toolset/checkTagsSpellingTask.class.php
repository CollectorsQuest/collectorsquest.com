<?php

/**
 * This task create csv file with list of tags, that have wrong spelling
 * pspell - required!
 */
class checkTagsSpellingTask extends sfBaseTask
{
  /* @var sfApplicationConfiguration */
  protected $configuration = null;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'debug';
    $this->name = 'tagsspelling';
  }

  protected function execute($arguments = array(), $options = array())
  {
    cqContext::createInstance($this->configuration);

    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    /** @var $propel PropelPDO */
    $propel = $databaseManager->getDatabase('propel')->getConnection();


    $stream = fopen(sfConfig::get('sf_cache_dir') . '/tags_spelling.csv', 'w+');

    fputcsv($stream, array('ID', 'Is triple', 'Objects count', 'Wrong', 'Correct', 'Update'));

    $count = iceModelTagQuery::create()->count();

    $i = $a= 0;
    $stmt = $propel->prepare('SELECT t.id, name, t.is_triple, t.triple_value,
    (SELECT COUNT(*) FROM tagging AS tg WHERE tg.tag_id = t.id) AS obj_count FROM tag AS t;');

    $pspell = pspell_new('en');

    $stmt->execute();
    while ($table = $stmt->fetch())
    {
      $a++;
      $words = preg_split('/\s/', $table['is_triple'] == 1 ? $table['triple_value'] : $table['name']);
      $corrected = '';
      $bad_tag = false;

      foreach ($words as $key => $word)
      {
        if (!pspell_check($pspell, $word) && $suggestions = pspell_suggest($pspell, $word))
        {
          //check only words
          if (preg_match('/^[a-z\']+$/i', $word))
          {
            $bad_tag = true;
            $corrected .= ' ' . current($suggestions);
          }
          else
          {
            $corrected .= ' ' . $word;
            $bad_tag = true;
//            if (count($words) > 1)
//            {
//              //words with something
//              $corrected .= ' ' . $word;
//            }
//            else
//            {
//              $corrected .= ' ' . $word;
//              $bad_tag = true;
//            }
          }
        }
        else
        {
          $corrected .= ' ' . $word;
        }
      }
      if ($bad_tag)
      {
//        if (strtolower($table['name']) != strtolower(trim($corrected)))
//        {
          $i++;
          fputcsv($stream, array($table['id'], $table['is_triple'],
            $table['obj_count'], $table['name'], trim($corrected)));
   //     }
      }

      echo sprintf("\r Completed: %.2f%%", round($a/$count, 4) * 100);

    }
    $this->log(sprintf("\n Total tags count: %s tags with wrong spelling count: %s ", $a, $i));


    $this->log('You can find report here ' . sfConfig::get('sf_cache_dir') . '/tags_spelling.csv');
  }

}
