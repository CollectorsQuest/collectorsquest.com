
/**
 * Reset the value of the <?php echo $column->getFullyQualifiedName() ?> column
 *
 * @param integer       $resetValue What number to reset the column to (default is 0)
 * @param boolean       $andSave Should a save() be performed after the reset
 * @param PropelPDO     $con A connection object
 *
 * @return <?php echo $objectClass ?> The object instance for a fluid interface
 */
public function reset<?php echo $column->getPhpName() ?>($resetValue = 0, $andSave = false, PropelPDO $con = null)
{
    $this->set<?php echo $column->getPhpName() ?>($resetValue);

    if ($andSave)
    {
        $this->save($con);
    }

    return $this;
}
