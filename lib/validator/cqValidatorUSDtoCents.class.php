<?php

/**
 * cqValidatorUSDtoCents
 */
class cqValidatorUSDtoCents extends cqValidatorPrice
{

  public function doClean($value)
  {
    parent::doClean($value);

    // Turn into cents
    $value = bcmul(cqStatic::floatval($value, 2), 100, 0);

    dd($value);

    return $value;
  }

}
