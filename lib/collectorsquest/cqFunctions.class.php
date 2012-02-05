<?php

class cqFunctions
{
  /**
   * @static
   *
   * @param  array   $values
   * @param  string  $callback
   *
   * @return array
   */
  public static function array_filter_recursive($values, $callback = null)
  {
    foreach ($values as $k => $value)
    {
      if (is_array($value))
      {
        $values[$k] = self::array_filter_recursive($value, $callback);
      }
    }

    return array_filter($values);
  }

  /**
   * @static
   *
   * @param  array  $values
   * @return array
   */
  public static function array_power_set($values)
  {
    // Initialize by adding the empty set
    $results = array(array());

    foreach ($values as $value)
    {
      foreach ($results as $combination)
      {
        array_push($results, array_merge(array($value), $combination));
      }
    }

    return $results;
  }

  /**
   * @static
   *
   * @param  array|PropelObjectCollection  $items
   * @param  integer  $columns
   * @param  boolean  $keep_keys
   *
   * @return array
   */
  public static function array_vertical_sort($items, $columns, $keep_keys = false)
  {
    $sorted = array();
    $total = count($items);
    $keys = ($items instanceof PropelObjectCollection) ? range(0, $items->count() - 1) : array_keys((array) $items);

    $rowCount = ceil($total / $columns);
    for ($i = 0; $i < $rowCount * $columns; $i++)
    {
      $index = ($i % $columns) * $rowCount + floor($i / $columns);

      if ($keep_keys === true)
      {
        $key = isset($keys[$index]) ? $keys[$index] : max($keys) + 1;
        $sorted[$key] = ($index < $total) ? $items[$key] : null;
      }
      else
      {
        $sorted[] = ($index < $total) ? $items[$index] : null;
      }
    }

    return $sorted;
  }

  /**
   * @static
   *
   * @param  int|float  $number
   * @param  int        $decimals
   * @param  string     $culture
   *
   * @return string
   */
  public static function number_format($number, $decimals = 0, $culture = 'bg_BG')
  {
    switch ($culture)
    {
      case 'en_US':
        $number = number_format($number, $decimals, '.', ',');
        break;
      case 'bg_BG':
      default:
        $number = number_format($number, $decimals, ',', ' ');
        break;
    }

    return $number;
  }

  /**
   * @static
   *
   * @param  string  $str1
   * @param  string  $str2
   *
   * @return int
   */
  public static function levenshtein($str1, $str2)
  {
    $str1 = mb_strtolower($str1, 'utf8');
    $str2 = mb_strtolower($str2, 'utf8');

    $len1 = mb_strlen($str1, 'utf8');
    $len2 = mb_strlen($str2, 'utf8');

    // strip common prefix
    $i = 0;
    do
    {
      if (mb_substr($str1, $i, 1, 'utf8') != mb_substr($str2, $i, 1, 'utf8'))
      {
        break;
      }

      $i++;

      $len1--;
      $len2--;
    }
    while($len1 > 0 && $len2 > 0);

    if ($i > 0)
    {
      $str1 = mb_substr($str1, $i, mb_strlen($str1, 'utf8'), 'utf8');
      $str2 = mb_substr($str2, $i, mb_strlen($str2, 'utf8'), 'utf8');
    }

    // strip common suffix
    $i = 0;
    do
    {
      if (mb_substr($str1, $len1-1, 1, 'utf8') != mb_substr($str2, $len2-1, 1, 'utf8'))
      {
        break;
      }
      $i++;
      $len1--;
      $len2--;
    }
    while($len1 > 0 && $len2 > 0);

    if ($i > 0)
    {
      $str1 = mb_substr($str1, 0, $len1, 'utf8');
      $str2 = mb_substr($str2, 0, $len2, 'utf8');
    }

    if ($len1 == 0)
    {
      return $len2;
    }
    if ($len2 == 0)
    {
      return $len1;
    }

    $v0 = range(0, $len1);
    $v1 = array();

    for ($i = 1; $i <= $len2; $i++)
    {
      $v1[0] = $i;
      $str2j = mb_substr($str2, $i - 1, 1, 'utf8');

      for ($j = 1; $j <= $len1; $j++)
      {
        $cost = (mb_substr($str1, $j - 1, 1, 'utf8') == $str2j) ? 0 : 1;

        $m_min = $v0[$j] + 1;
        $b = $v1[$j - 1] + 1;
        $c = $v0[$j - 1] + $cost;

        if ($b < $m_min)
        {
          $m_min = $b;
        }
        if ($c < $m_min)
        {
          $m_min = $c;
        }

        $v1[$j] = $m_min;
      }

      $vTmp = $v0;
      $v0 = $v1;
      $v1 = $vTmp;
    }

    return (int) @$v0[$len1];
  }

  /**
   * @static
   *
   * @param  string  $data
   * @param  string  $passwd
   * @param  string  $algo (sha1 or md5)
   *
   * @return string
   */
  public static function hmac($data, $passwd, $algo = 'sha1')
  {
    $algo = strtolower($algo);
    $p = array('md5' => 'H32', 'sha1' => 'H40');

    if (strlen($passwd) > 64)
    {
      $passwd = pack($p[$algo], $algo($passwd));
    }
    else if (strlen($passwd) < 64)
    {
      $passwd = str_pad($passwd, 64, chr(0));
    }

    $ipad = substr($passwd, 0, 64) ^ str_repeat(chr(0x36), 64);
    $opad = substr($passwd, 0, 64) ^ str_repeat(chr(0x5C), 64);

    return $algo($opad . pack($p[$algo], $algo($ipad . $data)));
  }

  /**
   * @static
   * @return string
   */
  public static function gethostname()
  {
    if (version_compare(PHP_VERSION, '5.3.0') >= 0)
    {
      $host = gethostname();
    }
    else
    {
      $host = php_uname('n');
    }

    return (string) $host;
  }
}
