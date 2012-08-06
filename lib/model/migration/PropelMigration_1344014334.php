<?php
/**
 * This migration will try to fix user error where
 * Collectors were putting images in "Alternate Views"
 * instead of creating new Collectibles
 */
class PropelMigration_1344014334
{

  public function preUp()
  {
    // add the pre-migration code here
  }

  public function postUp()
  {
    $collectible_ids = array(
      76504, 76713, 77351, 76594, 75157, 76644, 77328, 77357, 74667
    );

    $q = iceModelMultimediaQuery::create()
      ->filterByModel('Collectible')
      ->filterByModelId($collectible_ids)
      ->filterByIsPrimary(false)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $multimedia iceModelMultimedia[] */
    $multimedia = $q->find();

    /** @var $batch string */
    $batch = cqStatic::getUniqueId(32);

    foreach ($multimedia as $m)
    {
      /** @var $collection CollectorCollection */
      $collection = $m->getModelObject()->getCollectorCollection();

      $collectible = new Collectible();
      $collectible->setCollector($collection->getCollector());
      $collectible->setName($m->getName(), true);
      $collectible->setBatchHash($batch);
      $collectible->save();

      // Set the Collection after the collectible has been saved
      $collectible->setCollection($collection);

      /**
       * @var $_m iceModelMultimedia
       */
      if ($_m = $collectible->setThumbnail($m->getAbsolutePath('original'), true))
      {
        $_m->setName($m->getName());
        $_m->save();
      }

      $m->delete();
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
