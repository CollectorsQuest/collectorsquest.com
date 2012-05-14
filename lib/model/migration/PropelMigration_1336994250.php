<?php

class PropelMigration_1336994250
{

  /**
   * @param PropelMigrationManager $manager
   */
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
    return array(
      'blog'  => "
        INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_category`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES (null, 1, '2012-05-14 05:55:36', '2012-05-14 03:55:36', '<div class=\"row-fluid sidebar-title\">\r\n<div class=\"span12\">\r\n<h3 class=\"Chivo webfont\">Discover</h3>\r\n</div>\r\n</div>\r\n<ul class=\"unstyled sidebar-ul\">\r\n	<li><a href=\"/history/pawn-stars\">Seen on Pawn Stars</a></li>\r\n	<li><a href=\"/history/american-pickers\">Seen on American Pickers</a></li>\r\n	<li><a href=\"/blog\">Latest Blog Posts</a></li>\r\n</ul>', 'Home Page Discover (sidebar)', 0, 'c4bf2d50-9daa-11e1-a8b0-0800200c9a66', 'publish', 'closed', 'closed', '', 'home-page-discover-sidebar', '', '', '2012-05-14 07:15:41', '2012-05-14 11:15:41', '', 0, 'http://www.collectorsquest.com/blog/?post_type=cms_slot&p=', 0, 'cms_slot', '', 0);
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
      'blog'  => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
