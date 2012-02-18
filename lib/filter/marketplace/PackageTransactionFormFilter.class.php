<?php

/**
 * PackageTransaction filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 */
class PackageTransactionFormFilter extends BasePackageTransactionFormFilter
{

  public function configure()
  {
    $this->getWidget('expiry_date')->setOption('with_empty', false);
  }
}
