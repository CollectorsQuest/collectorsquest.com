<?php

/**
 * Migration to automatic set rating & machine tags for collectibles from predefined collections
 */
class PropelMigration_1351238578
{

  public function preUp($manager)
  {
    $sf_guard_user_id = 3;
    $collections = array(
      1442 => array(
        'machineTags' => array('Market:theme=cookie'), 'rating' => array('content' => 3, 'images' => 3)
      ),
      3948 => array(
        'machineTags' => array('Market:theme=smoke'), 'rating' => array('content' => 3, 'images' => 3)
      ),
      4280 => array(
        'machineTags' => array('Market:theme=holiday'), 'rating' => array('content' => 3, 'images' => 3)
      ),
      4282 => array(
        'machineTags' => array('Market:theme=kitchen'), 'rating' => array('content' => 3, 'images' => 3)
      ),
    );

    echo "Set rating & machine tags  \n";

    foreach ($collections as $id => $params)
    {
      /* @var $collection CollectorCollection */
      $collection = CollectorCollectionQuery::create()->findOneById($id);

      if ($collection)
      {
        echo sprintf("Processing collection Id: %s name: %s \n", $collection->getId(), $collection->getName());

        /* @var $collectibles Collectible[] */
        $collectibles = $collection->getCollectibles();

        $count = count($collectibles);

        foreach ($collectibles as $k => $collectible)
        {
          // adding machine tags
          if (isset($params['machineTags']) && count($params['machineTags']))
          {
            foreach ($params['machineTags'] as $tag)
            {
              $collectible->addTag($tag, true);
            }
          }

          //set rating
          if (isset($params['rating']) && count($params['rating']))
          {
            foreach ($params['rating'] as $dimantion => $ratingVal)
            {
              if (in_array($dimantion, array_keys(CollectibleRatingPeer::getDimensions())))
              {
                $method = sprintf('getAverage%sRating', ucfirst($dimantion));
                if (!$collectible->$method())
                {
                  $rating = new CollectibleRating();
                  $rating
                    ->setCollectible($collectible)
                    ->setRating($ratingVal)
                    ->setDimension($dimantion)
                    ->setSfGuardUserId($sf_guard_user_id)
                    ->save();
                }
              }

            }
          }


          $collectible->save();
          echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
        }
        echo "\r Completed: 100%  \n";
      }
    }

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
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
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
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }


}
