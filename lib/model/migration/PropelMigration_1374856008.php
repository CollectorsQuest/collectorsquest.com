<?php
/**
 * Import videos from magnify to wordpress
 */
class PropelMigration_1374856008
{
  const POST_AUTHOR_LOGIN = 'admin';

  private $tag_count = array(), $wp_author_id = null;

  public function preUp($manager)
  {
    if (!sfConfig::get('app_www_domain'))
    {
      throw new Exception('You should choose environment. Use parameter --env=');
    }
    //Lets find admin
    /** @var wpUser $admin */
    $admin = wpUserQuery::create()->findOneByUserLogin(self::POST_AUTHOR_LOGIN);
    if (!$admin)
    {
      throw new Exception('User with login "' . self::POST_AUTHOR_LOGIN
      . '" didn\'t found at wordpress DB, can not determinate post author ID');
    }

    $this->wp_author_id = $admin->getId();


    $magnify = cqStatic::getMagnifyClient();

    /** @var ContentFeed $videos */
    $total = $magnify->getContent()->browse(1, 1);

    //Lets cher magnify version
    if (!method_exists($total->first(), 'getCategory'))
    {
      throw new Exception('You use old version of magnify SDK, please update it at /lib/vendor/magnify');
    }


    $limit = 10;
    $i = 0;

    echo "\n Total videos: " . $total->getTotalResults() . "\n";

    for ($page = 1; $page <= floor($total->getTotalResults() / $limit); $page++)
    {
      /** @var ContentEntry[] $videos */
      $videos = $magnify->getContent()->browse($page, $limit);
      foreach ($videos as $video)
      {
        $i++;

        echo sprintf("\r Completed: %.2f%% %u. %s     ",
          round($i/$total->getTotalResults(), 4) * 100, $i, $video->getTitle());

        if ($videoUrl = $this->getSourceUrl($video->getIframeUrl()))
        {
          if (strpos($videoUrl, 'youtube.com') || strpos($videoUrl, 'vimeo.com') || strpos($videoUrl, 'youtu.be'))
          {
            $video->url = $videoUrl;

            $this->addEntry($video, $i);
          }
          else
          {
            echo sprintf("\r IGNORING - Video not supported %u. %s\n", $i, $video->getTitle());
          }
        }
        else
        {
          echo sprintf("\r IGNORING - Source url not detected %u. %s\n", $i, $video->getTitle());
        }
      }
    }

    echo "\r Completed: 100.00%             \n";
  }

  public function postUp($manager)
  {
    // add the post-migration code here
  }

  public function preDown($manager)
  {
    // add the pre-migration code here
  }

  public function postDown($manager)
  {
    // add the post-migration code here
  }

  /**
   * Get the SQL statements for the Up migration
   *
   * @return array list of the SQL strings to execute for the Up migration
   *               the keys being the datasources
   */
  public function getUpSQL()
  {
    return array (
      'propel' => '
    SET FOREIGN_KEY_CHECKS = 0;
    SET FOREIGN_KEY_CHECKS = 1;
',
      'blog' => '
    SET FOREIGN_KEY_CHECKS = 0;
    SET FOREIGN_KEY_CHECKS = 1;
',
      'icepique' => '
    SET FOREIGN_KEY_CHECKS = 0;
    SET FOREIGN_KEY_CHECKS = 1;
',
      'archive' => '
    SET FOREIGN_KEY_CHECKS = 0;
    SET FOREIGN_KEY_CHECKS = 1;
',
    );
  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return $this->getUpSQL();
  }

  /**
   * Trying to find video source url inside embed page
   * @param $iFrameUrl
   * @return string|bool
   */
  private function getSourceUrl($iFrameUrl)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,
      str_replace('/content/', '/player/container/1280/663/?remove_footer=1&content=', $iFrameUrl )
      . '&1264=663&widget_type_cid=svp');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    $data = curl_exec($ch);
    curl_close($ch);

    $url = preg_match('/"source_href" : "(.+)"/', $data, $match);

    return count($match) == 2 ? $match[1] : false;
  }

  /**
   * Save video as new post in wordpress
   *
   * @param ContentEntry $data
   * @param int
   * @return int
   */
  private function addEntry(ContentEntry $data, $index)
  {
    $postmeta = new wpPostMeta();
    $postmeta
      ->setMetaKey('_cq_video_url')
      ->setMetaValue($data->url);

    $postmeta_2 = new wpPostMeta();
    $postmeta_2
      ->setMetaKey('_video_url_fields')
      ->setMetaValue('a:1:{i:0;s:13:"_cq_video_url";}');

    $slug = explode('/', $data->alternate);
    $slug = strtolower(end($slug));

    //Add time shift to save original order
    $date = date('Y-m-d H:i', strtotime($data->updated_at) - $index * 1000);

    $post = new wpPost();
    $post
      ->setPostAuthor($this->wp_author_id)
      ->setPostDate($date)
      ->setPostDateGmt($date)
      ->setPostContent($data->getContent())
      ->setPostTitle($data->getTitle())
      ->setPostStatus('publish')
      ->setPostName($slug)
      ->setPostType('video')

      ->addwpPostMeta($postmeta)
      ->addwpPostMeta($postmeta_2)
    ;

    $post->save();


    foreach ($data->getCategory() as $cat)
    {
      if (isset($this->tag_count[strtolower($cat['term'])]))
      {
        $this->tag_count[strtolower($cat['term'])] ++;
      }
      else
      {
        $this->tag_count[strtolower($cat['term'])] = 1;
      }

      $term = wpTermQuery::create()
        ->findOneBySlug(strtolower($cat['term']));
      if (!$term)
      {
        $term = new wpTerm();
        $term
          ->setName($cat['label'])
          ->setSlug(strtolower($cat['term']))
          ->save();
      }

      $term_taxomony = wpTermTaxonomyQuery::create()
        ->filterByTermId($term->getTermId())
        ->filterByTaxonomy('video_tag')
        ->findOneOrCreate();
      $term_taxomony->save();

      $term_taxomony->setCount($this->tag_count[strtolower($cat['term'])])->save();

      $term_relationships = new wpTermRelationship();
      $term_relationships
        ->setObjectId($post->getId())
        ->setTermTaxonomyId($term_taxomony->getTermTaxonomyId())
        ->save();
    }

    $post->setGuid('http://'. sfConfig::get('app_www_domain') . '/blog/?post_type=video&#038;p=' . $post->getId())
      ->save();

    return $post->getId();
  }
}