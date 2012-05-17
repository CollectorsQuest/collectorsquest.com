<?php

class PropelMigration_1337283344
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp()
  {
    /** @var $tags iceModelTag[] */
    $tags = iceModelTagQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();

    foreach ($tags as $tag)
    {
      $slug = Utf8::slugify($tag->getName(), '-', true);

      $tag->setSlug($slug);
      $tag->save();
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
      'propel'  => "
        UPDATE `tag` SET `is_triple` = 0;
      "
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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
