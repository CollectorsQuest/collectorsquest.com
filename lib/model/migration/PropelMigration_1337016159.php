<?php

class PropelMigration_1337016159
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp()
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
      'propel'  => "
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `wp_cimy_uef_data`;
        CREATE TABLE `wp_cimy_uef_data` (
          `ID` bigint(20) NOT NULL AUTO_INCREMENT,
          `USER_ID` bigint(20) NOT NULL,
          `FIELD_ID` bigint(20) NOT NULL,
          `VALUE` text NOT NULL,
          PRIMARY KEY (`ID`),
          KEY `USER_ID` (`USER_ID`),
          KEY `FIELD_ID` (`FIELD_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        INSERT INTO `wp_cimy_uef_data` (`ID`, `USER_ID`, `FIELD_ID`, `VALUE`)
        VALUES
          (1,12,1,''),
          (2,1,1,''),
          (3,19,1,''),
          (4,14,1,''),
          (5,7,1,'batman, action figures, comics'),
          (6,20,1,''),
          (7,9,1,''),
          (8,3,1,''),
          (9,4,1,''),
          (10,21,1,''),
          (11,11,1,''),
          (12,17,1,''),
          (13,13,1,''),
          (14,10,1,''),
          (15,6,1,''),
          (16,5,1,''),
          (17,18,1,''),
          (18,15,1,''),
          (19,8,1,'');


        DROP TABLE IF EXISTS `wp_cimy_uef_fields`;
        CREATE TABLE `wp_cimy_uef_fields` (
          `ID` bigint(20) NOT NULL AUTO_INCREMENT,
          `F_ORDER` bigint(20) NOT NULL,
          `FIELDSET` bigint(20) NOT NULL DEFAULT '0',
          `NAME` varchar(20) DEFAULT NULL,
          `LABEL` text,
          `DESCRIPTION` text,
          `TYPE` varchar(20) DEFAULT NULL,
          `RULES` text,
          `VALUE` text,
          PRIMARY KEY (`ID`),
          KEY `F_ORDER` (`F_ORDER`),
          KEY `NAME` (`NAME`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


        INSERT INTO `wp_cimy_uef_fields` (`ID`, `F_ORDER`, `FIELDSET`, `NAME`, `LABEL`, `DESCRIPTION`, `TYPE`, `RULES`, `VALUE`)
        VALUES (1,1,0,'TAGS','Tags','These tags will be using for matching content to the user','text','a:12:{s:10:\"max_length\";i:255;s:12:\"can_be_empty\";b:1;s:4:\"edit\";s:18:\"edit_only_by_admin\";s:5:\"email\";b:0;s:16:\"advanced_options\";s:0:\"\";s:11:\"show_in_reg\";b:0;s:15:\"show_in_profile\";b:1;s:11:\"show_in_aeu\";b:0;s:14:\"show_in_search\";b:0;s:12:\"show_in_blog\";b:0;s:10:\"show_level\";s:1:\"2\";s:11:\"email_admin\";b:0;}','');


        DROP TABLE IF EXISTS `wp_cimy_uef_wp_fields`;
        CREATE TABLE `wp_cimy_uef_wp_fields` (
          `ID` bigint(20) NOT NULL AUTO_INCREMENT,
          `F_ORDER` bigint(20) NOT NULL,
          `NAME` varchar(20) DEFAULT NULL,
          `LABEL` text,
          `DESCRIPTION` text,
          `TYPE` varchar(20) DEFAULT NULL,
          `RULES` text,
          `VALUE` text,
          PRIMARY KEY (`ID`),
          KEY `F_ORDER` (`F_ORDER`),
          KEY `NAME` (`NAME`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        SET FOREIGN_KEY_CHECKS = 1;
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
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `wp_cimy_uef_data`;
        DROP TABLE IF EXISTS `wp_cimy_uef_fields`;
        DROP TABLE IF EXISTS `wp_cimy_uef_wp_fields`;

        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
