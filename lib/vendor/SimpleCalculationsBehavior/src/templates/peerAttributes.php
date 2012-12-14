
/** The columns handled by the SimpleCalculations behavior */
protected static $simpleCalculationsColumns = array(
<?php foreach ($columns as $column): ?>
    <?php echo $column->getConstantName(). ",\n"; ?>
<?php endforeach; ?>
);
