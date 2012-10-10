<?php

class RateTableBehaviorPeerBuilderModifier
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

  protected function getColumn($name)
  {
    return $this->behavior->getColumnForParameter($name);
  }

  protected function getColumnAttribute($name)
  {
    return strtolower($this->getColumn($name)->getName());
  }

  protected function getColumnConstant($name)
  {
    return strtoupper($this->getColumn($name)->getName());
  }

  protected function getColumnPhpName($name)
  {
    return $this->getColumn($name)->getPhpName();
  }

  protected function setBuilder($builder)
  {
    $this->builder = $builder;
    $this->objectClassname = $builder->getStubObjectBuilder()->getClassname();
    $this->peerClassname = $builder->getStubPeerBuilder()->getClassname();
  }

  public function staticAttributes($builder)
  {
    return $this->behavior->renderTemplate('rateTableAttributes', array(
      'max_rate'   => $this->getParameter('max_rate'),
      'dimensions' => $this->getParameter('dimensions')
    ));
  }

  public function staticMethods($builder)
  {
		$this->setBuilder($builder);
		$script = '';

		$this->addChoices($script);
    $this->addDimensions($script);

		return $script;
  }

  protected function addChoices(&$script)
  {
    $script .= "
/**
 * Return array of possibles rave values, from 1 to max_rate
 */
public static function getRateChoices()
{
  return array_combine(range(1, self::MAX_RATE), range(1, self::MAX_RATE));
}
";
  }

  protected function addDimensions(&$script)
  {
    $script .= "
/**
 * Return array of rate dimensions
 */
public static function getDimensions()
{
  return self::\$dimensions;
}
";
  }

}
