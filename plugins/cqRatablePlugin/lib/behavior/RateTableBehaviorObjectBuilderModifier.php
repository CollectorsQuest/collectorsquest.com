<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

/**
 * Behavior to adds nested set tree structure columns and abilities
 *
 * @author     François Zaninotto
 * @author     heltem <heltem@o2php.com>
 * @package    propel.generator.behavior.nestedset
 */
class RateTableBehaviorObjectBuilderModifier
{
	protected $behavior, $table, $builder, $objectClassname, $peerClassname;

	public function __construct($behavior)
	{
		$this->behavior = $behavior;
		$this->table = $behavior->getTable();
	}

	protected function getParameter($key)
	{
		return $this->behavior->getParameter($key);
	}

	protected function getColumnAttribute($name)
	{
		return strtolower($this->behavior->getColumnForParameter($name)->getName());
	}

	protected function getColumnPhpName($name)
	{
		return $this->behavior->getColumnForParameter($name)->getPhpName();
	}

	protected function setBuilder($builder)
	{
		$this->builder = $builder;
		$this->objectClassname = $builder->getStubObjectBuilder()->getClassname();
		$this->queryClassname = $builder->getStubQueryBuilder()->getClassname();
		$this->peerClassname = $builder->getStubPeerBuilder()->getClassname();
	}

	public function postSave($builder)
	{
    $objectClassname = $builder->getStubObjectBuilder()->getClassname();
		$peerClassname = $builder->getStubPeerBuilder()->getClassname();
		$queryClassname = $builder->getStubQueryBuilder()->getClassname();
    $class = $this->getParameter('ratable_class_name');

		$script = "
    /* @var \$object $class */
    \$object = \$this->get$class();
    \$c = new Criteria();
    \$c->add($peerClassname::DIMENSION, \$this->getDimension());

    //Set average rates for dimensions
    \$r = 0;
    /* @var \$rates {$objectClassname}[] */
    \$rates = \$object->get{$objectClassname}s(\$c);
    if (count(\$rates))
    {
      foreach (\$rates as \$rate)
      {
        \$r = \$r + \$rate->getRate();
      }
      \$object->setByName('average_' . \$this->getDimension() . '_rate', \$r / count(\$rates), BasePeer::TYPE_FIELDNAME);
    }

    //Set average total rates
    \$r = 0;
    foreach ($peerClassname::getDimensions() as \$dimension => \$label)
    {
      \$r = \$r + \$object->getByName('average_' . \$dimension . '_rate', BasePeer::TYPE_FIELDNAME);
    }
    \$object->setAverageRate(\$r / count($peerClassname::getDimensions()));

    \$object->save();
		";

		return $script;
	}

	public function objectMethods($builder)
	{
		$this->setBuilder($builder);
		$script = '';

    $this->addGetDimensionLabel($script, $builder);
    $this->addGetAverageRate($script, $builder);
    $this->addGetTotalRates($script, $builder);


		return $script;
	}

	protected function addGetDimensionLabel(&$script, $builder)
	{
    $peerClassname = $builder->getStubPeerBuilder()->getClassname();

    $script .= "
/**
* Get dimension label for current rate object
*/
public function getDimensionLabel()
{
  \$dimensions = $peerClassname::getDimensions();

  return \$dimensions[\$this->getDimension()];
}
";
	}

  protected function addGetAverageRate(&$script, $builder)
  {

    $class = $this->getParameter('ratable_class_name');

    $script .= "
/**
* Get average rate for current rate object dimension
*/
public function getAverageRate()
{
  /* @var \$object $class */
  \$object = \$this->get$class();

  return \$object->getByName('average_' . \$this->getDimension() . '_rate', BasePeer::TYPE_FIELDNAME);
}
";
  }

  protected function addGetTotalRates(&$script, $builder)
  {
    $objectClassname = $builder->getStubObjectBuilder()->getClassname();
    $peerClassname = $builder->getStubPeerBuilder()->getClassname();
    $class = $this->getParameter('ratable_class_name');

    $script .= "
/**
* Get count of rates with the same dimension
*/
public function getTotalRates()
{
  /* @var \$object $class */
  \$object = \$this->get$class();
  \$c = new Criteria();
  \$c->add($peerClassname::DIMENSION, \$this->getDimension());

  return \$object->count{$objectClassname}s(\$c);
}
";
  }
}





