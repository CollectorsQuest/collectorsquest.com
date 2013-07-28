<?php
/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1374856008.
 * Generated on 2013-07-26 12:26:48 by root
 */
class PropelMigration_1374856008
{
  private $tag_count = array();
  private $video_ids = array();

  public function preUp($manager)
  {
    $magnify = cqStatic::getMagnifyClient();

    /** @var ContentFeed $videos */
    $total = $magnify->getContent()->browse(1, 1);
    $limit = 10;
    $i = 0;

    for ($page = 1; $page <= floor($total->getTotalResults() / $limit); $page++)
    {
      /** @var ContentEntry[] $videos */
      $videos = $magnify->getContent()->browse($page, $limit);
      foreach ($videos as $video)
      {
        $i++;
        if ($videoUrl = $this->getSourceUrl($video->getIframeUrl()))
        {
          if (strpos($videoUrl, 'youtube.com') || strpos($videoUrl, 'vimeo.com') || strpos($videoUrl, 'youtu.be'))
          {
            $video->url = $videoUrl;

            $this->video_ids[$video->getId()] = $this->addEntry($video);
          }
        }

        echo $i . ' ' . $video->getTitle(). "\n";
      }
    }

    //TO DO Add playlists 

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
   * @return int
   */
  private function addEntry(ContentEntry $data)
  {
    $postmeta = new wpPostMeta();
    $postmeta
      ->setMetaKey('_cq_video_url')
      ->setMetaValue($data->url);

    $slug = explode('/', $data->alternate);
    $slug = strtolower(end($slug));

    $post = new wpPost();
    $post
      ->setPostAuthor(1) //TO DO do not use hardcoded id
      ->setPostDate($data->getUpdatedAt())     //TO DO wrong date
      ->setPostDateGmt($data->getUpdatedAt())
      ->setPostContent($data->getContent())
      ->setPostTitle($data->getTitle())
      ->setPostStatus('publish')
      ->setPostName($slug)
      ->setPostType('video')

      ->addwpPostMeta($postmeta) //TO DO no url in wp admin
    ;

    $post->save();


    foreach ($data->getCategory() as $cat)
    {
      if (isset($this->tag_count[strtolower($cat['term'])]))
      {
        $this->tag_count[strtolower($cat['term'])] ++ ;
      }
      else
      {
        $this->tag_count[strtolower($cat['term'])] = 1 ;
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

    $post->setGuid('http://www.collectorsquest.dev/blog/?post_type=video&#038;p=' . $post->getId())
      ->save(); //TO DO use domain from configs

    return $post->getId();
  }
}