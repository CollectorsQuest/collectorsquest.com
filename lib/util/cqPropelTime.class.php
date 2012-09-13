<?php

/**
 * cqPropelTime exposes propel timestampable style time formatters
 *
 * Useful in combination with ExtraProperties behavior
 */
class cqPropelTime
{
  /**
   * @param     mixed $v string, integer (timestamp), or DateTime value.
   *                     Empty strings are treated as NULL.
   * @return    string|null String formatted to "Y-m-d H:i:s"
   */
  public static function translateTimeToString($v)
  {
    /** @var $dt PropelDateTime */
    $dt = PropelDateTime::newInstance($v, null, 'DateTime');
    return $dt ? $dt->format('Y-m-d H:i:s') : null;
  }

  /**
   * @param     mixed $time     string, integer (timestamp), or DateTime value.
   * @param     string $format  The date/time format string (either date()-style or strftime()-style).
   *
   * @throws    RuntimeException
   * @return    mixed Formatted date/time value as string or
   *                  DateTime object (if format is NULL),
   *                  NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
   *
   */
  public static function format($time, $format = 'Y-m-d H:i:s')
  {
    if ($time === null)
    {
      return null;
    }

    if ($time === '0000-00-00 00:00:00')
    {
      // while technically this is not a default value of NULL,
      // this seems to be closest in meaning.
      return null;
    }
    else
    {
      try
      {
        $dt = new DateTime($time);
      }
      catch (Exception $x)
      {
        throw new RuntimeException(
          'Internally stored date/time/timestamp value could not be converted to DateTime: ' . var_export($time, true),
          $x
        );
      }
    }

    if ($format === null)
    {
      return $dt;
    }
    elseif (strpos($format, '%') !== false)
    {
      return strftime($format, $dt->format('U'));
    }
    else
    {
      return $dt->format($format);
    }
  }

}