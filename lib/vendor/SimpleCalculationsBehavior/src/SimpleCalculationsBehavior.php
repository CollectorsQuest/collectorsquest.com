<?php

/**
 * This file is part of the Simple Calculations Behavior
 * For the full copyright and license information, please view the README.md
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

/**
 * Gives a model class a number column for incrementing and decrementing
 *
 * @author      Ivan Plamenov Tanev aka Crafty_Shadow <vankata.t@gmail.com>
 */
class SimpleCalculationsBehavior extends Behavior
{
    // default parameters value
    protected $parameters = array(
        'columns'  => array(),
    );

    /**
     * {@inheritdoc}
     */
    public function addParameter($attribute)
    {
        if ('columns' === $attribute['name']) {
            if (false !== strpos($attribute['value'], '|')) {
              $values = explode('|', $attribute['value']); // propel style array
            } elseif (false !== strpos($attribute['value'], ', ')) {
              $values = explode(', ', $attribute['value']); // symfony style array
            } else {
              $values = (array) $attribute['value'];
            }

            if (1 < count($values)) {
                $this->parameters['columns'] = $values;
            } else {
                $this->parameters['columns'][] = $attribute['value'];
            }
        } else {
            parent::addParameter($attribute);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        $parameters  = parent::getParameters();
        $parameters['columns'] = implode($parameters['columns'], '|');

        return $parameters;
    }

    /**
     * Add the columns to the current table if not already set
     */
    public function modifyTable()
    {
        // create columns if they are not already added to the table
        foreach ($this->getParameter('columns') as $column) {
            if (!$this->getTable()->containsColumn($column)) {
                $this->getTable()->addColumn(array(
                    'name'          => $column,
                    'type'          => 'INTEGER',
                ));
            }
        }
    }

    public function staticAttributes($builder)
    {
      $script = '';

      $script .= $this->renderTemplate('peerAttributes', array(
          'columns' => $this->getTableColumns(),
      ));
      $script .= $this->renderTemplate('peerGetSimpleCalculationsColumns', array(
          'peerClassName' => $builder->getStubPeerBuilder()->getClassname(),
      ));

      return $script;
    }

    public function objectMethods($builder)
    {
        $script = '';

        $script .= $this->addObjectUpdateSimpleCalculationsColumn($builder);
        $script .= $this->addObjectResetSimpleCalcuationsColumn($builder);

        foreach ($this->getTableColumns() as $column) {
            $script .= $this->addObjectIncrementColumn($column);
            $script .= $this->addObjectDecrementColumn($column);
            $script .= $this->addObjectResetColumn($column);
        }

        return $script;
    }

    public function addObjectUpdateSimpleCalculationsColumn($builder)
    {
        return $this->renderTemplate('objectUpdateSimpleCalculationsColumn', array(
            'objectClass' => $this->getTable()->getPhpName(),
            'peerClassName' => $builder->getStubPeerBuilder()->getClassname(),
            'columns' => $this->getTableColumns(),
        ));
    }

    public function addObjectResetSimpleCalcuationsColumn($builder)
    {
        return $this->renderTemplate('objectResetSimpleCalculationsColumn', array(
            'objectClass' => $this->getTable()->getPhpName(),
            'peerClassName' => $builder->getStubPeerBuilder()->getClassname(),
            'columns' => $this->getTableColumns(),
        ));
    }

    public function addObjectIncrementColumn(Column $column)
    {
        return $this->renderTemplate('objectIncrementColumn', array(
            'column' => $column,
            'objectClass' => $this->getTable()->getPhpName(),
        ));
    }

    public function addObjectDecrementColumn(Column $column)
    {
        return $this->renderTemplate('objectDecrementColumn', array(
            'column' => $column,
            'objectClass' => $this->getTable()->getPhpName(),
        ));
    }

    public function addObjectResetColumn(Column $column)
    {
        return $this->renderTemplate('objectResetColumn', array(
            'column' => $column,
            'objectClass' => $this->getTable()->getPhpName(),
        ));
    }

    protected function getTableColumns()
    {
      if (!isset($this->table_columns))
      {
        foreach ($this->getParameter('columns') as $column_name)
        {
          $this->table_columns[] = $this->getTable()->getColumn($column_name);
        }
      }

      return $this->table_columns;
    }

}