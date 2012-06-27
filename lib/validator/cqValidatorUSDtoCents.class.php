<?php

/**
 * cqValidatorUSDtoCents
 */
class cqValidatorUSDtoCents extends cqValidatorPrice
{

  public function doClean($value)
  {
    $value = parent::doClean($value);

    // Turn into cents
    return bcmul($value, 100, 0);
  }

}
