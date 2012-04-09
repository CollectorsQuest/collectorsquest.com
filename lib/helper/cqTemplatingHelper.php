<?php

/**
 * Return either a distance of time in words or a culutre-aware formatted date
 * based on a cutoff limit. Example:
 *
 * cq_time_ago_in_words_or_exact_date(strtotime('2012-03-09 09:00:00'), '-7 days')
 * Will return one of the two, if the cutoff date has passed:
 *  - two days, seven hours ago
 *
 * @param     integer $from_time The date from which either the time in words will
 *                               be derived, or which will simply be formatted and
 *                               returned
 * @param     string $cutoff_date strtotime() compatible string defining the date
 *                               after which the exact date will be returned instead
 *                               of the distance of time in words
 * @param     $datetime_format   The format to be used when returning the exact date
 * @param     type $now          The current time
 * @return    string              Localized time string
 *
 * @see       DateHelper.php
 * @see       sfDateFormat
 * @see       sfDateTimeFormatInfo
 */
function time_ago_in_words_or_exact_date(
  $from_time,
  $cutoff_date = '-7 days',
  $datetime_format = 'On MMM d, yyyy, h:mm:s a',
  $now = null
) {
  if (null === $now)
  {
    $now = time();
  }

  if (!is_numeric($from_time))
  {
    $dt = new DateTime($from_time);
    $from_time = $dt->format('U');
  }

  $cutoff_date = strtotime($cutoff_date, $now);
  if ($from_time < $cutoff_date)
  {
    return format_date($from_time, $datetime_format);
  }
  else
  {
    return distance_of_time_in_short_words($from_time, $now, $with_secondary = true).' ago';
  }
}

/**
 * Returns a shorthand distance to time, like: "4h 37m", "1d 5h", "4m 23d"
 *
 * @param     integer $from_time      Distance from this time
 * @param     integer $to_time        To this time
 * @param     boolean $with_secondary If set to false the second component
 *                                    of the return string will be ommited,
 *                                    for example "4h 37m" will become "5h"
 * @return    string
 *
 * @see       DateHelper.php distance_of_time_in_words()
 */
function distance_of_time_in_short_words($from_time, $to_time = null, $with_secondary = true)
{
  $to_time = $to_time? $to_time: time();

  $distance_in_minutes = floor(abs($to_time - $from_time) / 60);

  $string = '';
  $parameters = array();

  if ($distance_in_minutes <= 1)
  {
      $string = $distance_in_minutes == 0 ? 'less than a min' : '1m';
  }
  else if ($distance_in_minutes >= 2 && $distance_in_minutes <= 44)
  {
    $string = '%minutes%m';
    $parameters['%minutes%'] = $distance_in_minutes;
  }
  else if ($distance_in_minutes >= 45 && $distance_in_minutes <= 89)
  {
    $string = '1h';

    if ($with_secondary && $distance_in_minutes > 60)
    {
      $string = '1h %minutes%m';
      $parameters['%minutes%'] = $distance_in_minutes - 60;
    }
  }
  else if ($distance_in_minutes >= 90 && $distance_in_minutes <= 1439)
  {
    $string = '%hours%h';
    $parameters['%hours%'] = round($distance_in_minutes / 60);

    if ($with_secondary && $distance_in_minutes % 60 > 0)
    {
      $string = '%hours%h %minutes%m';
      $parameters['%hours%'] = floor($distance_in_minutes / 60);
      $parameters['%minutes%'] = $distance_in_minutes % 60;
    }
  }
  else if ($distance_in_minutes >= 1440 && $distance_in_minutes <= 2879)
  {
    $string = '1d';

    if ($with_secondary && floor(($distance_in_minutes - 1440) / 60) > 0)
    {
      $string = '1d %hours%h';
      $parameters['%hours%'] = floor(($distance_in_minutes - 1440) / 60);
    }
  }
  else if ($distance_in_minutes >= 2880 && $distance_in_minutes <= 43199)
  {
    $string = '%days%d';
    $parameters['%days%'] = round($distance_in_minutes / 1440);

    if ($with_secondary && round(($distance_in_minutes % 1440) / 24) > 0)
    {
      $string = '%days%d %hours%h';
      $parameters['%days%'] = floor($distance_in_minutes / 1440);
      $parameters['%hours%'] = round(($distance_in_minutes % 1440) / 60);
    }
  }
  else if ($distance_in_minutes >= 43200 && $distance_in_minutes <= 86399)
  {
    $string = '1m';

    if ($with_secondary && round(($distance_in_minutes - 43200) / 1440) > 0)
    {
      $string = '1m %days%d';
      $parameters['%days%'] = round(($distance_in_minutes - 43200) / 1440);
    }
  }
  else if ($distance_in_minutes >= 86400 && $distance_in_minutes <= 525959)
  {
    $string = '%months%m';
    $parameters['%months%'] = round($distance_in_minutes / 43200);

    if ($with_secondary && round(($distance_in_minutes % 43200) / 1440) > 0)
    {
      $string = '%months%m %days%d';
      $parameters['%months%'] = floor($distance_in_minutes / 43200);
      $parameters['%days%'] = round(($distance_in_minutes % 43200) / 1440);
    }
  }
  else if ($distance_in_minutes >= 525960 && $distance_in_minutes <= 1051919)
  {
    $string = '1y';

    if ($with_secondary && round(($distance_in_minutes - 525960) / 43200) > 0)
    {
      $string = '1y %months%m';
      $parameters['%months%'] = round(($distance_in_minutes - 525960) / 43200);
    }
  }
  else
  {
    $string = 'over %years%y';
    $parameters['%years%'] = floor($distance_in_minutes / 525960);
  }

  if (sfConfig::get('sf_i18n'))
  {
    require_once sfConfig::get('sf_symfony_lib_dir').'/helper/I18NHelper.php';

    return __($string, $parameters);
  }
  else
  {
    return strtr($string, $parameters);
  }
}
