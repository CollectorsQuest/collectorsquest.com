<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1349958124.
 * Generated on 2012-10-11 08:22:04 by root
 */
class PropelMigration_1349958124
{

	public function preUp($manager)
	{
    echo "Importing Terms to Tags \n\n";
    /* @var $connection PropelPDO */
    $connection = $manager->getPdoConnection('propel');
    $sql = 'SELECT COUNT(*) FROM term_relationship';
    $stmt = $connection->prepare($sql);
    try
    {
      $stmt->execute();
    } catch (Exception $e)
    {
      echo "Looks like terms already deleted \n";
      return;
    }
    $count = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $count = $count[0];
    $stmt->closeCursor();

    $sql = 'SELECT * FROM term_relationship as r LEFT JOIN term as t ON r.term_id = t.id
            ORDER BY r.model, r.model_id';
    $stmt = $connection->prepare($sql);

    $stmt->execute();
    $objects = array();
    $lastKey = null;
    $k = 0;
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $k++;
      $key = sprintf('%s_%s', $r['model'], $r['model_id']);
      $peer = $r['model'].'Peer';

      if (!isset($objects[$key]))
      {
        $objects[$key] = $peer::retrieveByPK((integer) $r['model_id']);
      }
      if (is_object($objects[$key]))
      {
        $objects[$key]->addInternalTag($r['name']);
      }
      if ($lastKey != $key && isset($objects[$lastKey]))
      {
        if (is_object($objects[$key]))
        {
          $objects[$lastKey]->save();
        }
        unset($objects[$lastKey]);

      }
      $lastKey = $key;

      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }
    echo "\r Completed: 100%  \n";
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
        DROP TABLE IF EXISTS `term`;
        DROP TABLE IF EXISTS `term_relationship`;
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
