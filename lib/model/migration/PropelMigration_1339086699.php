<?php

class PropelMigration_1339086699
{

  public function preUp($manager)
  {
    /* @var $collectibles Collectible[] */
    $collectibles = CollectionQuery::create()
        ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
        ->find();

    foreach ($collectibles as $collectible)
    {
      $collectible->setDescription(cqMarkdown::doConvert($collectible->getDescription()));
      $collectible->save();
    }
  }

  public function postUp($manager)
  {
    // add the post-migration code here
  }

  public function preDown($manager)
  {
    /* @var $collectibles Collectible[] */
    $collectibles = CollectionQuery::create()
        ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
        ->find();

    foreach ($collectibles as $collectible)
    {
      $collectible->setDescription(cqMarkdownify::doConvert($collectible->getDescription()));
      $collectible->save();
    }
  }

  public function postDown($manager)
  {
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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog'   => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog'   => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}
