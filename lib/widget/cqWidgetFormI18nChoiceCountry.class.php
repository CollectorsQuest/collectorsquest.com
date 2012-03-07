<?php

class cqWidgetFormI18nChoiceCountry extends sfWidgetFormChoice
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * culture:   The culture to use for internationalized strings
   *  * countries: An array of country codes to use (ISO 3166)
   *  * add_empty: Whether to add a first empty value or not (false by default)
   *               If the option is not a Boolean, the value will be used as the text value
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoice
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('culture');
    $this->addOption('countries');
    $this->addOption('add_empty', false);

    // populate choices with all countries
    $culture = isset($options['culture']) ? $options['culture'] : 'en';

    // get the countries array based on $culture
    $countries = sfCultureInfo::getInstance($culture)->getCountries(isset($options['countries']) ? $options['countries'] : null);

    // We should not allow the 'Unknown or Invalid Region'
    unset($countries['ZZ']);

    $_countries = array();
    foreach (array('AU', 'CA', 'FR', 'DE', 'NL', 'GB', 'US') as $special)
    {
      $_countries[$special] = $countries[$special];
      unset($countries[$special]);
    }
    $_countries[] = '-------------------------';

    // merge the two parts again into one array
    $countries = array_merge($_countries, $countries);

    $addEmpty = isset($options['add_empty']) ? $options['add_empty'] : false;
    if (false !== $addEmpty)
    {
      $countries = array_merge(array('' => true === $addEmpty ? '' : $addEmpty), $countries);
    }

    $this->setOption('choices', $countries);
  }
}
