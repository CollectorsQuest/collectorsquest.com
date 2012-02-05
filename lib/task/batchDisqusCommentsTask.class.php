<?php

class batchDisqusCommentsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('hours', null, sfCommandOption::PARAMETER_REQUIRED, 'The number of hours ago', '24'),
    ));

    $this->namespace        = 'batch';
    $this->name             = 'disqus-comments';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [batch:disqus-comments|INFO] task connects to Disqus via the API and read the comments for the day and populates the
Comment table. This is done in order to have local copies of the comments.

Call it with:

  [php symfony batch:disqus-comments|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    require sfConfig::get('sf_lib_dir').'/vendor/Disqus.class.php';
    $disqus = new Disqus(sfConfig::get('app_credentials_disqus_user_key'), sfConfig::get('app_credentials_disqus_forum_key'));

    try
    {
      $threads = $disqus->get_updated_threads(212346, date('Y-m-d\TH:i', strtotime(sprintf('%d hours ago', $options['hours']))));
    }
    catch (DisqusException $e)
    {
      return false;
    }

    foreach ($threads as $thread)
    {
      $collection = $collectible = null;

      $identifier = split('-', $thread->identifier[0]);
      switch ($identifier[0])
      {
        case 'collection':
          $collection = CollectionPeer::retrieveByPK($identifier[1]);
          break;
        case 'collectible':
          if ($collectible = CollectiblePeer::retrieveByPK($identifier[1]))
          {
            $collection = $collectible->getCollection();
          }
          break;
        default:
          continue(2);
          break;
      }

      $options = array('start' => 0, 'limit' => 25, 'filter' => 'new');

      while ($posts = $disqus->get_thread_posts($thread->id, $options))
      {
        foreach ($posts as $post)
        {
          $comment = new Comment();
          $comment->setDisqusId($post->id);
          $comment->setParentId($post->parent_post);
          $comment->setCollection($collection);
          $comment->setCollectible($collectible);
          $comment->setIpAddress($post->ip_address);
          $comment->setBody($post->message);
          $comment->setCreatedAt($post->created_at);

          if ($post->is_anonymous)
          {
            $comment->setAuthorName($post->anonymous_author->name);
            $comment->setAuthorEmail($post->anonymous_author->email);
            $comment->setAuthorUrl($post->anonymous_author->url);
          }

          try
          {
            $comment->save();
          }
          catch (PropelException $e)
          {
            // the comment already exist, so no problem
          }
        }

        $options['start'] += 25;
      }
    }
  }
}
