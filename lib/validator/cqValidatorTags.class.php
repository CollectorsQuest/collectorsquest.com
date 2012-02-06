<?php

/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-09 01:23:36 +0300 (Thu, 09 Jun 2011) $
 * @version $Id: cqValidatorTags.class.php 2025 2011-06-08 22:23:36Z yanko $
 */
class cqValidatorTags extends sfValidatorBase
{
  public function clean($value)
  {
    return $this->doClean($value);
  }

  protected function doClean($value)
  {
    $values = array();
    
    if (!empty($value)) {
      $values = array_combine($value, $value);
    }
    
    return $values;
  }

}