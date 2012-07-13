<?php

ini_set('memory_limit', '512M');

/**
 * Fix missing Mutlimedia slugs
 */
class PropelMigration_1342190038
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    if (sfConfig::get('sf_environment') === 'dev') {
      return;
    }

    while (true)
    {
      $q = iceModelMultimediaQuery::create()
        ->filterBySlug('', Criteria::EQUAL)
        ->orderByCreatedAt(Criteria::DESC)
        ->limit(100);

      if (!$q->count()) {
        break;
      }

      /** @var $multimedia iceModelMultimedia[] */
      $multimedia = $q->find();

      foreach ($multimedia as $m)
      {
        $name = $m->getName();
        $m->setName(false);
        $m->setName($name);
      }

      $multimedia->save();

      echo ".";
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
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        UPDATE `multimedia` SET `model` = 'CollectorCollection'
         WHERE `model` = 'Collection';

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
