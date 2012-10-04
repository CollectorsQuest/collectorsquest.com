<?php

/**
 * Go over collections and collectibles and set the is_public flag appropriately
 *
 * https://basecamp.com/1759305/projects/824949-collectorsquest-com/todos/11981659-furthermore
 */
class PropelMigration_1347767852
{

  public function preUp()
  {
    // add the pre-migration code here
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

        UPDATE collectible
           SET is_public = (SELECT IF(COUNT(*) > 0, 1, 0) as is_public
                              FROM multimedia
                             WHERE model = "Collectible"
                               AND model_id = collectible.id AND is_primary = 1);

        UPDATE collector_collection
           SET is_public = (SELECT IF(COUNT(*) > 0, 1, 0) as is_public
                              FROM multimedia
                             WHERE model = "CollectorCollection"
                               AND model_id = collector_collection.id AND is_primary = 1);

        UPDATE collection
           SET is_public = (SELECT IF(COUNT(*) > 0, 1, 0) as is_public
                              FROM multimedia
                             WHERE model = "CollectorCollection"
                               AND model_id = collection.id AND is_primary = 1);

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
