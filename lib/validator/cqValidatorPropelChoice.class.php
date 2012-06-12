<?php

class cqValidatorPropelChoice extends sfValidatorPropelChoice
{
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if ($this->getOption('multiple'))
    {
      if (!is_array($value))
      {
        $value = array($value);
      }

      $value = array_filter($value);
      $value = array_values($value);
    }

    return parent::doClean($value);
  }
}
