<?php
require_once __DIR__ . '/RatingTableBehavior.php';

class RatableBehavior extends Behavior
{
  protected $parameters = array(
    'max_rating'        => 5,
    'dimensions'        => null,
    'rating_table'        => null,
    'user_table'        => null,
  );
  protected
    $dimensions = array(),
    $userTable,
    $ratingTable,
    $objectBuilderModifier,
    $queryBuilderModifier,
    $peerBuilderModifier;

  public function modifyTable()
  {
    /* @var $table Table */
    $table = $this->getTable();
    if ($userTable = $this->getParameter('user_table'))
    {
      /* @var $database Database */
      $database = $table->getDatabase();
      if (!$this->userTable = $database->getTable($userTable))
      {
        throw new InvalidArgumentException(sprintf(
          "Table '%s' not found, check 'user_table' parameter for the 'ratable' behavior in the '%s' table",
          $userTable,
          $table->getName()
        ));
      }
    }
    else
    {
      throw new InvalidArgumentException(
        sprintf(
          "You must define a 'user_table' parameter for the 'ratable' behavior in the '%s' table",
          $table->getName()
        )
      );
    }

    $this->addObjectColumns();
    $this->addObjectRatingTable();
    $this->addForeignKeyIfNone();
  }

  protected function addObjectColumns()
  {
    // add the average column;
    if (!$this->getTable()->hasColumn('average_rating'))
    {
      $this->getTable()->addColumn(array(
        'name'         => 'average_rating',
        'type'         => 'FLOAT',
        'defaultValue' => null
      ));
    }
    // add the average column for dimensions;
    foreach ($this->getDimensions() as $dimension => $label)
    {
      if (!$this->getTable()->hasColumn('average_' . $dimension . '_rating'))
      {
        $this->getTable()->addColumn(array(
          'name'         => 'average_'.$dimension.'_rating',
          'type'         => 'FLOAT',
          'defaultValue' => null
        ));
      }
    }
  }

  protected function addObjectRatingTable()
  {
    $table = $this->getTable();
    $database = $table->getDatabase();
    $ratingsTableName = $this->getParameter('rating_table')
      ? $this->getParameter('rating_table')
      : $table->getName() . '_rating';

    if (!$database->hasTable($ratingsTableName))
    {
      $ratingTable = $database->addTable(array(
        'name'      => $ratingsTableName,
        'phpName'   => $this->getRatingTableName(),
        'package'   => $table->getPackage(),
        'schema'    => $table->getSchema(),
        'namespace' => $table->getNamespace() ? '\\' . $table->getNamespace() : null,
      ));
      $ratingTable->isRatingTable = true;
      // add id column
      $pk = $ratingTable->addColumn(array(
        'name'					=> 'id',
        'autoIncrement' => 'true',
        'type'					=> 'INTEGER',
        'primaryKey'    => 'true'
      ));
      $pk->setNotNull(true);
      $pk->setPrimaryKey(true);


      $ratingTableBehavior = new RatingTableBehavior();
      $ratingTableBehavior->setName('rating_table');
      $ratingTableBehavior->addParameter(array(
        'name' => 'dimensions',
        'value' => $this->getDimensions()
      ));
      $ratingTableBehavior->addParameter(array(
        'name' => 'ratable_class_name',
        'value' => $this->getTable()->getPhpName()
      ));
      $ratingTable->addBehavior($ratingTableBehavior);

      // every behavior adding a table should re-execute database behaviors
      foreach ($database->getBehaviors() as $behavior)
      {
        $behavior->modifyDatabase();
      }

      // add dimension columns
      if ($this->getDimensions() != array())
      {
        $ratingTable->addColumn(array(
          'name'      => 'dimension',
          'type'      => 'VARCHAR',
          'required'  => 'true'
        ));
      }
      // add rating columns
      $ratingTable->addColumn(array(
        'name'      => 'rating',
        'type'      => 'INTEGER',
        'required'  => 'true'
      ));

      $this->ratingTable = $ratingTable;

    }
    else
    {
      $this->ratingTable = $database->getTable($ratingsTableName);
    }

  }

  protected function addForeignKeyIfNone()
  {
    $table = $this->getTable();
    foreach ($this->ratingTable->getForeignKeys() as $fk)
    {
      if ($table->getCommonName() === $fk->getForeignTableCommonName())
      {
        return ;
      }
    }
    // create the foreign key for object
    $fk = new ForeignKey();
    $fk->setForeignTableCommonName($table->getCommonName());
    $fk->setForeignSchemaName($table->getSchema());
    $fk->setOnDelete(ForeignKey::CASCADE);
    $fk->setOnUpdate(null);
    $tablePKs = $table->getPrimaryKey();
    foreach ($table->getPrimaryKey() as $key => $column)
    {
      $ref_column = $column->getAttributes();
      $ref_column['name'] = sprintf('%s_%s', $table->getName(), $ref_column['name']);
      $ref_column['required'] = 'true';
      $ref_column['primaryKey'] = 'false';
      $ref_column['autoIncrement'] = 'false';
      $ref_column = $this->ratingTable->addColumn($ref_column);
      $fk->addReference($ref_column, $column);
    }
    $this->ratingTable->addForeignKey($fk);

    // create the foreign key for user
    /** @var $table Table */
    $table = $this->userTable;
    $fk = new ForeignKey();
    $fk->setForeignTableCommonName($table->getCommonName());
    $fk->setForeignSchemaName($table->getSchema());
    $fk->setOnDelete(ForeignKey::CASCADE);
    $fk->setOnUpdate(null);

    $tablePKs = $table->getPrimaryKey();
    foreach ($table->getPrimaryKey() as $key => $column)
    {
      $ref_column = $column->getAttributes();
      $ref_column['name'] = sprintf('%s_%s', $table->getName(), $ref_column['name']);
      $ref_column['required'] = 'true';
      $ref_column['primaryKey'] = 'false';
      $ref_column['autoIncrement'] = 'false';
      $ref_column = $this->ratingTable->addColumn($ref_column);
      $fk->addReference($ref_column, $column);
    }
    $this->ratingTable->addForeignKey($fk);

  }

  public function getRatingTable()
  {
    return $this->ratingTable;
  }

  protected function getRatingTableName()
  {
    return $this->getTable()->getPhpName() . 'Rating';
  }

  private function getDimensions()
  {
    if ($this->dimensions == array() && $this->getParameter('dimensions') !== null)
    {
      $dimensions =  explode(',', $this->getParameter('dimensions'));
      foreach ($dimensions as $dimension)
      {
        $key = strtolower(trim($dimension));
        $key = sfToolkit::pregtr($key, array('/[^a-z0-9-_\s]/' => '','/[\-\s]/' => '_'));
        $this->dimensions[$key] = trim($dimension);
      }
    }

    return $this->dimensions;
  }


}
