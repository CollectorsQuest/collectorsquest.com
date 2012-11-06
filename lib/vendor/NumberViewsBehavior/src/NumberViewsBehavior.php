<?php

/**
 * This file is part of the Number Views Behavior
 * For the full copyright and license information, please view the README.md
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

/**
 * Gives a model class a number of views column and helper methods
 *
 * @author      Ivan Plamenov Tanev aka Crafty_Shadow <vankata.t@gmail.com>
 */
class NumberViewsBehavior extends Behavior
{
    // default parameters value
    protected $parameters = array(
        'column'  => 'num_views',
    );

    /**
     * Add the num_views column to the current table if not already set
     */
    public function modifyTable()
    {
        if (!$this->getTable()->containsColumn($this->getParameter('column'))) {
            $this->getTable()->addColumn(array(
                'name'          => $this->getParameter('column'),
                'type'          => 'INTEGER',
            ));
        }
    }

    public function objectMethods($builder)
    {
        $script = '';
        $script .= $this->addObjectIncrementNumberViews();
        $script .= $this->addObjectResetNumberViews();

        return $script;
    }

    public function addObjectIncrementNumberViews()
    {
        return $this->renderTemplate('objectIncrementNumberViews', array(
            'column' => $this->getTable()->getColumn($this->getParameter('column')),
            'objectClass' => $this->getTable()->getPhpName(),
        ));
    }

    public function addObjectResetNumberViews()
    {
        return $this->renderTemplate('objectResetNumberViews', array(
            'column' => $this->getTable()->getColumn($this->getParameter('column')),
            'objectClass' => $this->getTable()->getPhpName(),
        ));
    }

}