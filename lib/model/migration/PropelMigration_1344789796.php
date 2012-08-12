<?php

/**
 * Update iceSpamControlPlugin
 */
class PropelMigration_1344789796
{

  protected static $enum_modifications = array(
      'Field' => array(
          'email'   => 0,
          'phone'   => 1,
          'ip'      => 2,
          'regex'   => 3,
          'session' => 4,
      ),
      'Credentials' => array(
          'all'     => 1, // this was not present in the original DB,
                      // so just consider it the same as "read"
          'read'    => 1,
          'create'  => 2,
          'edit'    => 3,
          'comment' => 4,
      ),
  );

	public function preUp($manager)
	{
    // remove empty spam control records
    iceModelSpamControlQuery::create()
      ->filterByValue('')
      ->delete();

    foreach (self::$enum_modifications as $field => $modification)
    {
      foreach ($modification as $old => $new)
      {
        // Change all old enum values to their new propel_enum value
        iceModelSpamControlQuery::create()
          ->where('iceModelSpamControl.'.$field.' = ?',  $old)
          ->update(array($field => $new));
      }
    }
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
    foreach (self::$enum_modifications as $field => $modification)
    {
      foreach ($modification as $old => $new)
      {
        // a reverse of the preUp
        iceModelSpamControlQuery::create()
          ->where('iceModelSpamControl.'.$field.' = ?',  $new)
          ->update(array($field => $old));
      }
    }
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

        ALTER TABLE `spam_control`
          MODIFY COLUMN `field` TINYINT DEFAULT 3 NOT NULL;

        ALTER TABLE `spam_control`
          MODIFY COLUMN `credentials` TINYINT DEFAULT 1 NOT NULL;

        DROP INDEX `spam_control_U_1` ON `spam_control`;
        CREATE UNIQUE INDEX `spam_control_U_1`
          ON `spam_control` (`field`, `credentials`, `value`);

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

        DROP INDEX `spam_control_U_1` ON `spam_control`;
        CREATE UNIQUE INDEX `spam_control_U_1`
          ON `spam_control` (`field`, `value`);

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }
}