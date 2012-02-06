<?php

class cqPropelData extends sfPropelData
{
  /**
   * Implements the abstract loadDataFromArray method and loads the data using the generated data model.
   *
   * @param array   $data  The data to be loaded into the data source
   *
   * @throws Exception If data is unnamed.
   * @throws sfException If an object defined in the model does not exist in the data
   * @throws sfException If a column that does not exist is referenced
   */
  public function loadDataFromArray($data)
  {
    if ($data === null)
    {
      // no data
      return;
    }

    foreach ($data as $class => $datas)
    {
      $class = trim($class);

      $tableMap = $this->dbMap->getTable(constant(constant($class.'::PEER').'::TABLE_NAME'));

      $column_names = call_user_func_array(array(constant($class.'::PEER'), 'getFieldNames'), array(BasePeer::TYPE_FIELDNAME));

      // iterate through datas for this class
      // might have been empty just for force a table to be emptied on import
      if (!is_array($datas))
      {
        continue;
      }

      foreach ($datas as $key => $data)
      {
        // create a new entry in the database
        if (!class_exists($class))
        {
          throw new InvalidArgumentException(sprintf('Unknown class "%s".', $class));
        }

        $obj = new $class();

        if (!$obj instanceof BaseObject)
        {
          throw new RuntimeException(sprintf('The class "%s" is not a Propel class. This probably means there is already a class named "%s" somewhere in symfony or in your project.', $class, $class));
        }

        if (!is_array($data))
        {
          throw new InvalidArgumentException(sprintf('You must give a name for each fixture data entry (class %s).', $class));
        }

        foreach ($data as $name => $value)
        {
          if (is_array($value) && 's' == substr($name, -1))
          {
            // many to many relationship
            $this->loadMany2Many($obj, substr($name, 0, -1), $value);

            continue;
          }

          $isARealColumn = true;
          try
          {
            $column = $tableMap->getColumn($name);
          }
          catch (PropelException $e)
          {
            $isARealColumn = false;
          }

          // foreign key?
          if ($isARealColumn)
          {
            if ($column->isForeignKey() && null !== $value)
            {
              $relatedTable = $this->dbMap->getTable($column->getRelatedTableName());
              if (!isset($this->object_references[$relatedTable->getPhpName().'_'.$value]))
              {
                throw new InvalidArgumentException(sprintf('The object "%s" from class "%s" is not defined in your data file.', $value, $relatedTable->getPhpName()));
              }
              $value = $this->object_references[$relatedTable->getPhpName().'_'.$value]->getByName($column->getRelatedName(), BasePeer::TYPE_COLNAME);
            }
          }

          if ($name == 'id' && $value != '~' && stripos($class, 'i18n') !== false)
          {
            $params = array($value, $data['culture'], $this->con);

            if ($obj1 = call_user_func_array(array(constant($class.'::PEER'), 'retrieveByPk'), $params))
            {
              $obj = clone $obj1;
            }
          }

          if (false !== $pos = array_search($name, $column_names))
          {
            $obj->setByPosition($pos, $value);
          }
          else if (is_callable(array($obj, $method = 'set'.sfInflector::camelize($name))))
          {
            $obj->$method($value);
          }
          else
          {
            throw new InvalidArgumentException(sprintf('Column "%s" does not exist for class "%s".', $name, $class));
          }
        }
        $obj->save($this->con);

        // save the object for future reference
        if (method_exists($obj, 'getPrimaryKey'))
        {
          $this->object_references[Propel::importClass(constant(constant($class.'::PEER').'::CLASS_DEFAULT')).'_'.$key] = $obj;
        }
      }
    }
  }
}