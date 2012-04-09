<?php
/**
 * File: cqValidatorExpiryDate.class.php
 *
 * @author zecho
 * @version $Id$
 *
 */

class cqValidatorExpiryDate extends sfValidatorDate
{

  /**
   * Converts an array representing a date to a timestamp.
   *
   * The array can contains the following keys: year, month, day, hour, minute, second
   *
   * @param  array $value  An array of date elements
   *
   * @return int A timestamp
   */
  protected function convertDateArrayToString($value)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($value[$key]) && !preg_match('#^\d+$#', $value[$key]) && !empty($value[$key]))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    // if one date value is empty, all others must be empty too
    $empties =
        (!isset($value['year']) || !$value['year'] ? 1 : 0) +
            (!isset($value['month']) || !$value['month'] ? 1 : 0);
    if ($empties > 0 && $empties < 3)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    else if (3 == $empties)
    {
      return $this->getEmptyValue();
    }

    if (!checkdate(intval($value['month']), 1, intval($value['year'])))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    $clean = sprintf(
      "%04d-%02d",
      intval($value['year']),
      intval($value['month'])
    );

    return $clean;
  }

}
