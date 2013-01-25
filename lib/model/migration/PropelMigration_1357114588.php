<?php

/**
 * Move collection Flea Market Finds (4642) of the main CQ account to
 * a separate account - HanShotFirst (18053)
 */
class PropelMigration_1357114588
{
    const COLLECTION_ID = 4642;
    const TARGET_COLLECTOR_ID = 18053;

    public function preUp($manager)
    {
       // update the collector id for the collection
       $collection = CollectorCollectionQuery::create()->findPk(self::COLLECTION_ID);
       if (!$collection)
       {
         return 0;
       }

       $collection->setCollectorId(self::TARGET_COLLECTOR_ID);
       $collection->save();

       // and then update it for each collectible attached to the collection
       foreach ($collection->getCollectibles() as $collectible)
       {
         /* @var $collectible Collectible */
         $collectible->setCollectorId(self::TARGET_COLLECTOR_ID);
       }
       $collection->getCollectibles()->save();
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
