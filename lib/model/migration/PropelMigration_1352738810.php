<?php

/**
 * Check if Collectibles are really public - they have name, description, tags and image
 */
class PropelMigration_1352738810
{

  public function preUp($manager)
  {
    $q = CollectionCollectibleQuery::create()
     ->filterByIsPublic(true);

    $collectibles = $q->find();
    $count = $q->count();

    $ids = array();

    foreach ($collectibles as $a => $collectible)
    {
      if ($collectible instanceof CollectionCollectible)
      {
        $is_public = true;

        /* @var $collectible CollectionCollectible */
        if (!$collectible->getName())
        {
          $is_public = false;
        }
        else if (!$collectible->getDescription())
        {
          $is_public = false;
        }
        else if (!$collectible->getTags())
        {
          $is_public = false;
        }
        else if (!$collectible->getPrimaryImage(Propel::CONNECTION_WRITE))
        {
          $is_public = false;
        }

        if (!$is_public)
        {
          $collectible->setIsPublic($is_public);
          $collectible->save();

          $ids[] = $collectible->getId();
        }
        echo sprintf("\r Completed: %.2f%%", round($a/$count, 4) * 100);
      }
    }

    echo 'Changed public status of Collectible IDs:' . print_r($ids);
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
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}
