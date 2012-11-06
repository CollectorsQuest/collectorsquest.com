
/**
 * Increments the value of the <?php echo $column->getFullyQualifiedName() ?> column
 *
 * @param boolean $andSave Should a save() be performed after the increment
 * @param PropelPDO $con A connection object
 *
 * @return <?php echo $objectClass ?> The object instance for a fluid interface
 */
public function increment<?php echo $column->getPhpName() ?>($andSave = false, PropelPDO $con = null)
{
  $this->set<?php echo $column->getPhpName() ?>(
    $this->get<?php echo $column->getPhpName() ?>() + 1
  );

  if ($andSave)
  {
    $this->save($con);
  }

  return $this;
}
