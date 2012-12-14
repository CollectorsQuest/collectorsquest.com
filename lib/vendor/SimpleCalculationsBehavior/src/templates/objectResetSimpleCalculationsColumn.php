
/**
 * Resets the value of a SimpleCalculations column
 *
 * @param string      $column The column name (in TYPE_PHPNAME by default)
 * @param integer     $resetValue What number to reset the column to (default is 0)
 * @param boolean     $andSave Should a save() be performed after the reset
 * @param string      $columnFieldnameType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
 * @param PropelPDO   $con A connection object
 *
 * @return <?php echo $objectClass ?> The object instance for a fluid interface
 */
public function resetColumn($column, $resetValue = 0, $andSave = false, $columnFieldnameType = BasePeer::TYPE_PHPNAME, PropelPDO $con = null)
{
    $key = <?php echo $peerClassName ?>::translateFieldName(
        $column,
        $columnFieldnameType,
        BasePeer::TYPE_COLNAME
    );

    if (!in_array($key, <?php echo $peerClassName; ?>::getSimpleCalculationsColumns()))
    {
        throw new RuntimeException(sprintf(
            "The column %s is not a one of the SimpleCalculations columns: [%s]",
            $column,
            implode(', ',<?php echo $peerClassName; ?>::getSimpleCalculationsColumns())
        ));
    }

    $colPhpName = <?php echo $peerClassName ?>::translateFieldName(
        $column,
        $columnFieldnameType,
        BasePeer::TYPE_PHPNAME
    );

    $setter = 'set' . $colPhpName;

    $this->$setter($resetValue);

    if ($andSave)
    {
        $this->save($con);
    }

    return $this;
}
