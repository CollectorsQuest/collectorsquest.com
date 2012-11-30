<?php

/**
 * Country select widget that is geoip aware, and all
 * choices are from the geo_country table
 */
class cqWidgetFormChoiceGeoIpCountry extends sfWidgetFormSelect
{

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * countries: An array of country codes to use (ISO 3166)
   *  * common_countries: An array of countries to be show before the others
   *  * common_separator: String to separate common from all, removed if false
   *  * keep_common_in_full_list: False by default, common removed from all
   *  * remote_address: If suplied GeoIP will be used to determine the country
   *                    to be show as the first select option
   *  * add_empty: Whether to add a first empty value or not (false by default)
   *               If the option is not a Boolean, the value will be used as the
   *               text value
   *  * add_worldwide: Whether to add "Worldwide" as an option to
   *                   the common countries
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormSelect
   */
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('countries', false);
    $this->addOption('common_countries', array('AU', 'CA', 'FR', 'DE', 'NL', 'GB', 'US'));
    $this->addOption('common_separator', '-------------------------');
    $this->addOption('add_worldwide', false);
    $this->addOption('keep_common_in_full_list', false);
    $this->addOption('remote_address', null);
    $this->addOption('add_empty', true);

    // so that we don't get an exception for required option
    $this->addOption('choices', array());
  }

  /**
   * Will call buildCountriesList if "choices" option is empty
   *
   * @return array
   */
  public function getChoices()
  {
    $countries = $this->getOption('choices');
    if (empty($countries))
    {
      $this->buildCountriesList();
    }

    return parent::getChoices();
  }

  /**
   * Update the "choices" array with countries, based on widget options
   */
  public function buildCountriesList()
  {
    // get the countries array based on GeoCountry
    $countries = iceModelGeoCountryQuery::create()
      ->_if(is_array($this->getOption('countries')))
        ->filterByIso3166($this->getOption('countries'), Criteria::IN)
      ->_endif()
      ->find()->toKeyValue('Iso3166', 'Name');

    // prepare common countries
    $common_countries = array();
    foreach ($this->getOption('common_countries') as $country)
    {
      if (isset($countries[$country]))
      {
        $common_countries[$country] = $countries[$country];
      }

      if (!$this->getOption('keep_common_in_full_list'))
      {
        unset($countries[$country]);
      }
    }

    // unset worldwide from the list of all countries
    unset($countries['ZZ']);
    if ($this->getOption('add_worldwide'))
    {
      // and add it back to the list of common countries if desired
      $common_countries['ZZ'] = 'Worldwide';
    }

    // add the separator between common and all countries, unless "false"
    if (false !== $this->getOption('common_separator'))
    {
      $common_countries['-'] = $this->getOption('common_separator');
    }

    // merge common with all countries
    $countries = $common_countries + $countries;

    // if remote_address option is supplied use GeoIP to guess first country choice
    if (false !== $this->getOption('remote_address') && function_exists('geoip_country_code_by_name'))
    {
      if ($country_code = @geoip_country_code_by_name($this->getOption('remote_address')))
      {
        if (isset($countries[$country_code]))
        {
          $tmp = array(
              $country_code => $countries[$country_code]
          );
          unset($countries[$country_code]);
          $countries = $tmp + $countries;
        }
      }
    }

    if (false !== $addEmpty = $this->getOption('add_empty'))
    {
      $countries = array('' => true === $addEmpty ? '' : $addEmpty) + $countries;
    }

    $this->setOption('choices', $countries);
  }
}