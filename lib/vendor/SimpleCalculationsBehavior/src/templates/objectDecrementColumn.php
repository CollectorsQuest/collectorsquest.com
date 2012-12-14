
/**
 * Decrements the value of the <?php echo $column->getFullyQualifiedName() ?> column
 *
 * @param integer       $decrementBy What number to decrement the <?php echo $column->getFullyQualifiedName() ?> column by (default is 1)
 * @param boolean       $andSave Should a save() be performed after the decrement
 * @param PropelPDO     $con A connection object
 *
 * @return <?php echo $objectClass ?> The object instance for a fluid interface
 */
public function decrement<?php echo $column->getPhpName() ?>($decrementBy = 1, $andSave = false, PropelPDO $con = null)
{
    $this->set<?php echo $column->getPhpName() ?>(
        $this->get<?php echo $column->getPhpName() ?>() - (int) $decrementBy
    );

    if ($andSave)
    {
        $this->save($con);
    }

    return $this;
}
