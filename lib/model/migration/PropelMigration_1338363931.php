<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1338363931.
 * Generated on 2012-05-30 10:20:09 by root
 */
class PropelMigration_1338363931
{

  static protected $csv_file = '/migrations/content_categories_20120530.csv';

  static protected $known_categories;

	public function preUp($manager)
  {
    // add the pre-migration code here
  }

	public function postUp($manager)
	{
    // Create root node
    $content_category_root = new ContentCategory();
    $content_category_root->makeRoot();
    $content_category_root->setName('Root');
    $content_category_root->save();

    $csv_full_name = sfConfig::get('sf_data_dir') . self::$csv_file;
    if ( ($handle = fopen($csv_full_name, 'r')) !== false )
    {
      // first we grap the root category
      $root_category = ContentCategoryQuery::create()
        ->findRoot();

      while ( ($data = fgetcsv($handle)) !== false )
      {
        $collection_category_id = trim(array_shift($data));

        $collection_ids = array_shift($data);
        if ('' !== $collection_ids)
        {
          // convert a string like "1423,1406, 1438" to array even with missing whitespaces
          $collection_ids = explode(',', str_replace(' ', '', $collection_ids));
        }
        else
        {
          $collection_ids = array();
        }

        // for each row, we set the parent category to the root
        $parent_category = $root_category;

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
          $category_id = $this->existingCategory($category_name, $parent_category);
          if (!$category_id)
          {
            // new category
            $category = new ContentCategory();
            $category->setName($category_name);
            $category->insertAsFirstChildOf($parent_category);
            $category->save();
          }
          else
          {
            $category = ContentCategoryPeer::retrieveByPK($category_id);
          }

          // add to known categories
          self::$known_categories[$category->getName() . $parent_category->getId()] = $category->getId();

          // and make it the parent category for the next item in the row
          $parent_category = $category;
        } // while category name columns

        // if this row had a valid collection category id set
        if ($collection_category_id)
        {
          $parent_category->setCollectionCategoryId($collection_category_id);
          $parent_category->save();
        }

        // if this row had valid collection ids set
        if (count($collection_ids))
        {
          CollectorCollectionQuery::create()
            ->filterByPrimaryKeys($collection_ids)
            ->update(
            array('ContentCategoryId' => $parent_category->getId()),
            $con = null,
            // required for Concrete Inheritance tables
            $forceIndividualSaves = true
          );
        }
      } // while csv rows

      fclose($handle);
    }
    else
    {
      echo "\n\n" . sprintf("\n\nThe migration csv file '%s' could not be opened for reading.\n", $csv_full_name);

      // fail the migration
      return false;
    }
	}

  public function existingCategory($name, ContentCategory $parent_category)
  {
    return isset(self::$known_categories[$name . $parent_category->getId()])
      ? self::$known_categories[$name . $parent_category->getId()]
      : ContentCategoryQuery::create()
        ->filterByName($name)
        ->descendantsOf($parent_category)
        ->select('Id')
        ->findOne();
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
        TRUNCATE TABLE `content_category`;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'archive' => '
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE `content_category_archive`;
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
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
	}

}
