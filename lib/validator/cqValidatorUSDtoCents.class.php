<?php

/**
 * cqValidatorUSDtoCents
 */
class cqValidatorUSDtoCents extends sfValidatorInteger
{

  public function doClean($value)
  {
    $value = bcmul(cqStatic::floatval($value, 2), 100);

    return parent::doClean($value);
  }

}
