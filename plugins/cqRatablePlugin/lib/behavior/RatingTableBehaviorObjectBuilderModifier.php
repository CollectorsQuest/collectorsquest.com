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
class RatingTableBehaviorObjectBuilderModifier
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
    $foreign_columns = $this->getParameter('foreign_columns');

    $fk = '';
    foreach ($foreign_columns as $column)
    {
      $fk .= sprintf("->add(%s::%s, \$object->getId())\n", $peerClassname, strtoupper($column));
    }

    $script = "
/* @var \$object $class */
\$object = \$this->get$class();
\$c = new Criteria();
\$c
  ->add($peerClassname::DIMENSION, \$this->getDimension())
  ->clearSelectColumns()
  $fk
  ->addAsColumn('average_dimension_rating', sprintf('(SUM(%s) / COUNT(*))', $peerClassname::RATING))
  ->setLimit(1);
\$stmt = $peerClassname::doSelectStmt(\$c, \$con);
\$average_dimension_rating = \$stmt->fetch(PDO::FETCH_COLUMN);

/* @var \$dimension_field_name string */
\$dimension_field_name = sprintf('average_%s_rating', \$this->getDimension());

//Set average ratings for dimensions
if (\$object->getByName(\$dimension_field_name, BasePeer::TYPE_FIELDNAME) != \$average_dimension_rating) {
  \$object->setByName(\$dimension_field_name, \$average_dimension_rating, BasePeer::TYPE_FIELDNAME);

  //Set average total ratings
  \$r = 0;
  foreach ($peerClassname::getDimensions() as \$dimension => \$label) {
    \$r = \$r + \$object->getByName(sprintf('average_%s_rating', \$dimension), BasePeer::TYPE_FIELDNAME);
  }
  \$average_rating = \$r / count($peerClassname::getDimensions());

  \$object->setAverageRating(\$average_rating);

  \$sql = sprintf(
    'UPDATE %s SET %s = %f, %s = %f WHERE %s = %d',
    {$class}Peer::TABLE_NAME,
    {$class}Peer::translateFieldName(\$dimension_field_name, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME),
    \$average_dimension_rating,
    {$class}Peer::AVERAGE_RATING,
    \$average_rating,
    {$class}Peer::ID,
    \$object->getId()
  );
  \$object->resetModified(
    {$class}Peer::translateFieldName(\$dimension_field_name, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME)
  );
  \$object->resetModified({$class}Peer::AVERAGE_RATING);
  \$con->exec(\$sql);
}
";
    return $script;
  }

  public function objectMethods($builder)
  {
    $this->setBuilder($builder);
    $script = '';

    $this->addGetDimensionLabel($script, $builder);
    $this->addGetAverageRating($script, $builder);
    $this->addGetTotalRatings($script, $builder);


    return $script;
  }

  protected function addGetDimensionLabel(&$script, $builder)
  {
    $peerClassname = $builder->getStubPeerBuilder()->getClassname();

    $script .= "
/**
* Get dimension label for current rating object
*/
public function getDimensionLabel()
{
  \$dimensions = $peerClassname::getDimensions();

  return \$dimensions[\$this->getDimension()];
}
";
  }

  protected function addGetAverageRating(&$script, $builder)
  {

    $class = $this->getParameter('ratable_class_name');

    $script .= "
/**
* Get average rating for current rating object dimension
*/
public function getAverageRating()
{
  /* @var \$object $class */
  \$object = \$this->get$class();

  return \$object->getByName('average_' . \$this->getDimension() . '_rating', BasePeer::TYPE_FIELDNAME);
}
";
  }

  protected function addGetTotalRatings(&$script, $builder)
  {
    $objectClassname = $builder->getStubObjectBuilder()->getClassname();
    $peerClassname = $builder->getStubPeerBuilder()->getClassname();
    $class = $this->getParameter('ratable_class_name');

    $script .= "
/**
* Get count of ratings with the same dimension
*/
public function getTotalRatings()
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





