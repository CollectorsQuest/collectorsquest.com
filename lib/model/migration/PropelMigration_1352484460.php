<?php

/**
 * Create a content category "Art" and add back its orphants
 */
class PropelMigration_1352484460
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    /* @var $root ContentCategory */
    $root = ContentCategoryQuery::create()->findRoot();

    /* @var $art ContentCategory */
    $art = ContentCategoryQuery::create()
      ->childrenOfRoot()
      ->filterByName('Art')
      ->findOneOrCreate();

    try
    {
      $root->addChild($art);
      $art->save();
    }
    catch (Exception $e)
    {
      $art->moveToLastChildOf($root);
      $art->save();
    }

    $ids = array(163, 171, 176, 182, 183, 186, 187, 200, 201, 203, 206, 215, 232, 269, 333);

    foreach ($ids as $id)
    {
      $category = ContentCategoryQuery::create()->findOneById($id);
      $category->moveToLastChildOf($art);
      $category->save();
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
    return array (
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
    return array (
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
