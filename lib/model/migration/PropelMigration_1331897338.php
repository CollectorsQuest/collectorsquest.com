<?php

class PropelMigration_1331897338
{

	public function preUp(PropelMigrationManager $manager)
	{

	}

	public function postUp(PropelMigrationManager $manager)
	{
    /* @var $pdo PropelPDO */
    $pdo = $manager->getPdoConnection('propel');
    $pdo->exec("UPDATE collector_collection SET collector_collection.num_items = (SELECT COUNT(id) FROM collection_collectible WHERE collection_id = collector_collection.id)");
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
		return array();
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
		return array();
	}

}
