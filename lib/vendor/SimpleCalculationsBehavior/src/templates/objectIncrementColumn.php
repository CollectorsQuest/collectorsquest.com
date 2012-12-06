
/**
 * Increments the value of the <?php echo $column->getFullyQualifiedName() ?> column
 *
 * @param integer       $incrementBy What number to increment the <?php echo $column->getFullyQualifiedName() ?> column by (default is 1)
 * @param boolean       $andSave Should a save() be performed after the increment
 * @param PropelPDO     $con A connection object
 *
 * @return <?php echo $objectClass ?> The object instance for a fluid interface
 */
public function increment<?php echo $column->getPhpName() ?>($incrementBy = 1, $andSave = false, PropelPDO $con = null)
{
    $this->set<?php echo $column->getPhpName() ?>(
        $this->get<?php echo $column->getPhpName() ?>() + (int) $incrementBy
    );

    if ($andSave)
    {
        $this->save($con);
    }

    return $this;
}
