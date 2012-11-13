<?php

/**
 * Remove comments pointing to deleted objects
 */
class PropelMigration_1352493869
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    echo "Remove comments pointing to deleted objects:\n";

    $count = CommentQuery::create()->count();
    $comments = CommentQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();
    $deletions = 0;
    foreach ($comments as $k => $comment)
    {
      /* @var $comment Comment */
      if (!$comment->getModelObject())
      {
        $comment->delete();
        $deletions++;
      }
      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }

    echo "\r Completed: 100.00% \n";
    echo sprintf(" %d comments were deleted. \n", $deletions);
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
