<?php

/**
 * Nuke the "Antiquities" category and
 * assign all dependent objects to category "Other" (level one)
 *
 * https://basecamp.com/1759305/projects/824949-collectorsquest-com/todos/15141073-nuke-the
 */
class PropelMigration_1347688038
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // "Antiquities" level one category
    $antiquities = ContentCategoryQuery::create()
      ->findOneById(146);

    // Make sure we still have the category
    if (!$antiquities)
    {
      return true;
    }

    $children = $antiquities->getChildrenWithCollections();

    foreach ($children as $child)
    {
      /** @var $collections CollectorCollection[] */
      $collections = CollectorCollectionQuery::create()
        ->filterByContentCategoryWithDescendants($child)
        ->find();

      foreach ($collections as $collection)
      {
        $collection->setContentCategoryId(3560);
        $collection->save();
      }
    }

    $children->delete();
    $antiquities->delete();

    return true;
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
    return array (
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
