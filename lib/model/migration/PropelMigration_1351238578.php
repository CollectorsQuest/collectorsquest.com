<?php

/**
 * Migration to automatic set rating & machine tags for collectibles from predefined collections
 */
class PropelMigration_1351238578
{

  public function preUp()
  {
    // add the pre-migration code here
  }

  public function postUp()
  {
    $sf_guard_user_id = 8;
    $collections = array(
      1442 => array(
        'machineTags' => array('market:theme=cookie'), 'rating' => array('content' => 3, 'images' => 3)
      ),
      3948 => array(
        'machineTags' => array('market:theme=smoke'), 'rating' => array('content' => 3, 'images' => 3)
      ),
      4280 => array(
        'machineTags' => array('market:theme=holiday'), 'rating' => array('content' => 3, 'images' => 3)
      ),
      4282 => array(
        'machineTags' => array('market:theme=kitchen'), 'rating' => array('content' => 3, 'images' => 3)
      ),
    );

    echo "Set rating & machine tags  \n";

    foreach ($collections as $collectionId => $params)
    {
      /* @var $collection CollectorCollection */
      $collection = CollectorCollectionPeer::retrieveByPK($collectionId);

      if ($collection)
      {
        echo sprintf("Processing collection Id: %s name: %s \n", $collection->getId(), $collection->getName());

        /*
        //adding machine tags for collection
        if (isset($params['machineTags']) && count($params['machineTags']))
        {
          foreach ($params['machineTags'] as $tag)
          {
            $collection->addTag($tag, true);
          }
        }

        //set rating for collection
        if (isset($params['rating']) && count($params['rating']))
        {
          foreach ($params['rating'] as $dimantion => $ratingVal)
          {
            if (in_array($dimantion, array_keys(CollectorCollectionRatingPeer::getDimensions())))
            {
              $method = sprintf('getAverage%sRating', ucfirst($dimantion));
              if (!$collection->$method())
              {
                $rating = new CollectorCollectionRating();
                $rating
                  ->setCollectorCollection($collection)
                  ->setRating($ratingVal)
                  ->setDimension($dimantion)
                  ->setSfGuardUserId($sf_guard_user_id)
                  ->save();
              }
            }

          }
        }
        */

        /* @var $collectibles PropelObjectCollection|Collectible[] */
        $collectibles = $collection->getCollectibles();

        $count = count($collectibles);

        foreach ($collectibles as $k => $collectible)
        {
          // Adding machine tags
          if (isset($params['machineTags']) && count($params['machineTags']))
          {
            $collectible->addTag($params['machineTags'], true);
          }

          // set rating
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
                    ->setCollectibleId($collectible->getId())
                    ->setRating($ratingVal)
                    ->setDimension($dimantion)
                    ->setSfGuardUserId($sf_guard_user_id)
                    ->save();
                }
              }

            }
          }

          echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
        }

        $collectibles->save();
        echo "\r Completed: 100%  \n";
      }
    }
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
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        UPDATE tag SET `name` = REPLACE(`name`, 'Market:theme=', 'market:theme=') WHERE triple_namespace = 'market';
        UPDATE tag SET triple_namespace = 'market' WHERE triple_namespace = 'Market';

        SET FOREIGN_KEY_CHECKS = 1;
      ",
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
