<?php
require_once __DIR__ . '/RateTableBehavior.php';

class RatableBehavior extends Behavior
{
  protected $parameters = array(
    'max_rate'        => 5,
    'dimensions'        => null,
    'rate_table'        => null,
    'user_table'        => null,
  );
  protected
    $dimensions = array(),
    $userTable,
    $rateTable,
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
    $this->addObjectRateTable();
    $this->addForeignKeyIfNone();
  }

  protected function addObjectColumns()
  {
    // add the average column;
    if (!$this->getTable()->hasColumn('average_rate'))
    {
      $this->getTable()->addColumn(array(
        'name'         => 'average_rate',
        'type'         => 'FLOAT',
        'defaultValue' => null
      ));
    }
    // add the average column for dimensions;
    foreach ($this->getDimensions() as $dimension => $label)
    {
      if (!$this->getTable()->hasColumn('average_' . $dimension . '_rate'))
      {
        $this->getTable()->addColumn(array(
          'name'         => 'average_'.$dimension.'_rate',
          'type'         => 'FLOAT',
          'defaultValue' => null
        ));
      }
    }
  }

  protected function addObjectRateTable()
  {
    $table = $this->getTable();
    $database = $table->getDatabase();
    $ratesTableName = $this->getParameter('rate_table')
      ? $this->getParameter('rate_table')
      : $table->getName() . '_rate';

    if (!$database->hasTable($ratesTableName))
    {
      $rateTable = $database->addTable(array(
        'name'      => $ratesTableName,
        'phpName'   => $this->getRateTableName(),
        'package'   => $table->getPackage(),
        'schema'    => $table->getSchema(),
        'namespace' => $table->getNamespace() ? '\\' . $table->getNamespace() : null,
      ));
      $rateTable->isRateTable = true;
      // add id column
      $pk = $rateTable->addColumn(array(
        'name'					=> 'id',
        'autoIncrement' => 'true',
        'type'					=> 'INTEGER',
        'primaryKey'    => 'true'
      ));
      $pk->setNotNull(true);
      $pk->setPrimaryKey(true);


      $rateTableBehavior = new RateTableBehavior();
      $rateTableBehavior->setName('rate_table');
      $rateTableBehavior->addParameter(array(
        'name' => 'dimensions',
        'value' => $this->getDimensions()
      ));
      $rateTableBehavior->addParameter(array(
        'name' => 'ratable_class_name',
        'value' => $this->getTable()->getPhpName()
      ));
      $rateTable->addBehavior($rateTableBehavior);

      // every behavior adding a table should re-execute database behaviors
      foreach ($database->getBehaviors() as $behavior)
      {
        $behavior->modifyDatabase();
      }

      // add dimension columns
      if ($this->getDimensions() != array())
      {
        $rateTable->addColumn(array(
          'name'      => 'dimension',
          'type'      => 'VARCHAR',
          'required'  => 'true'
        ));
      }
      // add rate columns
      $rateTable->addColumn(array(
        'name'      => 'rate',
        'type'      => 'INTEGER',
        'required'  => 'true'
      ));

      $this->rateTable = $rateTable;

    }
    else
    {
      $this->rateTable = $database->getTable($ratesTableName);
    }

  }

  protected function addForeignKeyIfNone()
  {
    $table = $this->getTable();
    foreach ($this->rateTable->getForeignKeys() as $fk)
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
      $ref_column = $this->rateTable->addColumn($ref_column);
      $fk->addReference($ref_column, $column);
    }
    $this->rateTable->addForeignKey($fk);

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
      $ref_column = $this->rateTable->addColumn($ref_column);
      $fk->addReference($ref_column, $column);
    }
    $this->rateTable->addForeignKey($fk);

  }

  public function getRateTable()
  {
    return $this->rateTable;
  }

  protected function getRateTableName()
  {
    return $this->getTable()->getPhpName() . 'Rate';
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
