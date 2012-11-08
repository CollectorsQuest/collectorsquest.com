<?php

/**
 * Migration to fix triple tags with whitespaces
 */
class PropelMigration_1352105985
{

  public function preUp($manager)
  {
    $count = iceModelTagQuery::create()->filterByIsTriple(true)->count();
    $propel = Propel::getConnection(
      iceModelTagPeer::DATABASE_NAME, Propel::CONNECTION_WRITE
    );

    $stmt = $propel->prepare('SELECT * FROM tag WHERE is_triple = 1;');

    /* @var $tags array of wrong tags */
    $tags = array();
    $a = 0;

    echo "\nLooking for wrong tags\n";

    $stmt->execute();
    while ($table = $stmt->fetch())
    {
      $a++;
      if (trim($table['triple_value']) != $table['triple_value'])
      {
        $tags[sprintf(
          '%s:%s=%s', $table['triple_namespace'], $table['triple_key'], trim($table['triple_value'])
        )][]
          = $table['id'];
      }
      echo sprintf("\r Completed: %.2f%%", round($a/$count, 4) * 100);
    }

    $count = count($tags);
    if ($count)
    {
      $a = 0;
      echo sprintf("\nPrecessing %s tags\n", $count);
      foreach ($tags as $name => $ids)
      {
        $a++;
        // getting correct tag
        /* @var $original iceModelTag */
        $original = iceModelTagPeer::retrieveOrCreateByTagname(substr($name, 0, 64));
        $original->save();
        foreach ($ids as $id)
        {
          $c1 = new Criteria();
          $c1->add(iceModelTaggingPeer::TAG_ID, $id);

          $c2 = new Criteria();
          $c2->add(iceModelTaggingPeer::TAG_ID, $original->getId());
          //update relationship's
          BasePeer::doUpdate($c1, $c2, Propel::getConnection());
          //delete wrong tag
          iceModelTagQuery::create()->filterById($id)->delete();
        }
        echo sprintf("\r Completed: %.2f%%", round($a/$count, 4) * 100);
      }
      echo "\r Completed: 100%\n";
    }
    else
    {
      echo "\nNo tags to process\n";
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
    return array(
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