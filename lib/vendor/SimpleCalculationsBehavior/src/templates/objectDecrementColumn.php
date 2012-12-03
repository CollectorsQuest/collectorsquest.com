
/**
 * Decrements the value of the <?php echo $column->getFullyQualifiedName() ?> column
 *
 * @param boolean $andSave Should a save() be performed after the decrement
 * @param integer $decrementBy What number to decrement the <?php echo $column->getFullyQualifiedName() ?> by (default is 1)
 * @param PropelPDO $con A connection object
 *
 * @return <?php echo $objectClass ?> The object instance for a fluid interface
 */
public function decrement<?php echo $column->getPhpName() ?>($andSave = false, $decrementBy = 1, PropelPDO $con = null)
{
  $this->set<?php echo $column->getPhpName() ?>(
    $this->get<?php echo $column->getPhpName() ?>() - (integer) $decrementBy
  );

  if ($andSave)
  {
    $this->save($con);
  }

  return $this;
}
