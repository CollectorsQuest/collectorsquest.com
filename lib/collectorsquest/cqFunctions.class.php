<?php

class cqFunctions extends IceFunctions
{
  /**
   * A 3-in-1 method to explode a string, trim the values and remove the duplicates
   *
   * @param  string  $delimiter
   * @param  string  $string
   *
   * @return array
   */
  public static function explode($delimiter, $string)
  {
    $array = explode($delimiter, (string) $string);
    $array = array_map('trim', $array);
    $array = array_filter($array);

    return $array;
  }
}
