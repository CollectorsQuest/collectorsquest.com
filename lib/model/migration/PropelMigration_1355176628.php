<?php

/**
 * Remove &nbsp; from
 */
class PropelMigration_1355176628
{

    public function preUp($manager)
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
          'propel' => "
            UPDATE collectible SET `description` = REPLACE(`description`, '&nbsp;', ' ');
            UPDATE collector_collection SET `description` = REPLACE(`description`, '&nbsp;', ' ');
            UPDATE collection SET `description` = REPLACE(`description`, '&nbsp;', ' ');
          ",
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
