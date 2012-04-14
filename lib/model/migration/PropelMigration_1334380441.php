<?php

/**
 * Migration for importing content_categories data
 */
class PropelMigration_1334380441
{

  static protected $csv_file = '/migrations/content_categories_20120411.csv';

	public function preUp($manager)
	{
    $csv_full_name = sfConfig::get('sf_data_dir') . self::$csv_file;

    if ( ($handle = fopen($csv_full_name, 'r')) !== false )
    {
      // first we grap the root category
      $root_category = ContentCategoryQuery::create()
          ->findRoot();

      // if needed we can populate name_to_id from the database,
      // and thus be able to perform update from a fresh csv file:
      // $name_to_id = ContentCategoryQuery::create()->find()->toKeyValue('Name', 'Id');
      $name_to_id = array();

      while ( ($data = fgetcsv($handle)) !== false )
      {
        // for each row, we set the parent category to the root
        $parent_category_id = $root_category->getId();;

        // and work our way right (data is in parent > child order)
        while ( ($category_name = array_shift($data)) !== null )
        {
          // prepare the category name for the database
          $category_name = ucwords(trim($category_name));

          if ('' == $category_name)
          {
            // empty string, nothing more to do here, go to next row
            break;
          }

          // check if we have already inserted this category into the db
          if (!isset($name_to_id[$category_name]))
          {
            // we have not yet inserted this category
            $category = new ContentCategory();
            $category->setName($category_name);
            $category->insertAsLastChildOf(ContentCategoryPeer::retrieveByPK($parent_category_id));
            $category->save();

            // add to the list of inserted categories
            $name_to_id[$category_name] = $category->getId();

            // and make it the parent category for the next item in the row
            $parent_category_id = $category->getId();
          }
          else
          {
            // we have inserted this category before, grab its id and set it
            // as the parent for the next item in the row
            $parent_category_id = $name_to_id[$category_name];
          }
        }
      }

      fclose($handle);
    }
    else
    {
      echo "\n\n" . sprintf("\n\nThe migration csv file '%s' could not be opened for reading.\n", $csv_full_name);

      // fail the migration
      return false;
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

    $content_category_root = new ContentCategory();
    $content_category_root->makeRoot();
    $content_category_root->setName('Root');
    $content_category_root->save();
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

# propel requires a query to be present, or the migration version is not incemented

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

TRUNCATE TABLE content_category;

SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}