<?php

/**
 * A singular migration to introduce all changes related to the organizations module
 */
class PropelMigration_1350902144
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
    // create the OrganizationType records
    foreach (OrganizationTypePeer::$organization_types as $type => $name)
    {
      $organization_type = new OrganizationType();
      $organization_type
        ->setType($type)
        ->setName($name)
        ->save();
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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        -- ---------------------------------------------------------------------
        -- org_organization
        -- ---------------------------------------------------------------------

        DROP TABLE IF EXISTS `org_organization`;

        CREATE TABLE `org_organization`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `founder_id` INTEGER NOT NULL,
          `name` VARCHAR(255) NOT NULL,
          `url` VARCHAR(255),
          `phone` VARCHAR(255),
          `description` TEXT,
          `type` TINYINT,
          `type_other` VARCHAR(255),
          `badge_image` VARCHAR(255) NOT NULL,
          `referral_code` VARCHAR(50),
          `access` TINYINT DEFAULT 1 NOT NULL,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          `slug` VARCHAR(255),
          PRIMARY KEY (`id`),
          UNIQUE INDEX `org_organization_U_1` (`referral_code`),
          UNIQUE INDEX `org_organization_slug` (`slug`(255)),
          INDEX `org_organization_FI_1` (`founder_id`),
          INDEX `org_organization_FI_2` (`type`),
          CONSTRAINT `org_organization_FK_1`
            FOREIGN KEY (`founder_id`)
            REFERENCES `collector` (`id`),
          CONSTRAINT `org_organization_FK_2`
            FOREIGN KEY (`type`)
            REFERENCES `org_organization_type` (`type`)
        ) ENGINE=InnoDB;

        -- ---------------------------------------------------------------------
        -- org_organization_type
        -- ---------------------------------------------------------------------

        DROP TABLE IF EXISTS `org_organization_type`;

        CREATE TABLE `org_organization_type`
        (
          `type` TINYINT NOT NULL,
          `name` VARCHAR(255) NOT NULL,
          `description` TEXT,
          PRIMARY KEY (`type`)
        ) ENGINE=InnoDB;

        -- ---------------------------------------------------------------------
        -- org_organization_membership
        -- ---------------------------------------------------------------------

        DROP TABLE IF EXISTS `org_organization_membership`;

        CREATE TABLE `org_organization_membership`
        (
          `organization_id` INTEGER NOT NULL,
          `collector_id` INTEGER NOT NULL,
          `type` TINYINT NOT NULL,
          `joined_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`organization_id`,`collector_id`),
          INDEX `org_organization_membership_FI_2` (`collector_id`),
          CONSTRAINT `org_organization_membership_FK_1`
            FOREIGN KEY (`organization_id`)
            REFERENCES `org_organization` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `org_organization_membership_FK_2`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        -- ---------------------------------------------------------------------
        -- org_organization_membership_request
        -- ---------------------------------------------------------------------

        DROP TABLE IF EXISTS `org_organization_membership_request`;

        CREATE TABLE `org_organization_membership_request`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `organization_id` INTEGER NOT NULL,
          `collector_id` INTEGER NOT NULL,
          `is_invitation` TINYINT(1) DEFAULT 0 NOT NULL,
          `status` TINYINT NOT NULL,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `org_organization_membership_request_FI_1` (`organization_id`),
          INDEX `org_organization_membership_request_FI_2` (`collector_id`),
          CONSTRAINT `org_organization_membership_request_FK_1`
            FOREIGN KEY (`organization_id`)
            REFERENCES `org_organization` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `org_organization_membership_request_FK_2`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;


        # This restores the fkey checks, after having unset them earlier
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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `org_organization`;

        DROP TABLE IF EXISTS `org_organization_type`;

        DROP TABLE IF EXISTS `org_organization_membership`;

        DROP TABLE IF EXISTS `org_organization_membership_request`;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
	}

}