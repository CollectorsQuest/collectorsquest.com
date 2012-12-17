<?php

class cqWidgetFormI18nChoiceIceModelGeoCountry extends cqWidgetFormI18nChoiceCountry
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * culture:   The culture to use for internationalized strings
   *  * add_empty: Whether to add a first empty value or not (false by default)
   *               If the option is not a Boolean, the value will be used as the
   *               text value
   *  * add_worldwide: Whether to add "Worldwide" as an option
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoice
   */
  protected function configure($options = array(), $attributes = array())
  {
    // directly set the countries option to those available from iceModelGeoCountry
    $options['countries'] = iceModelGeoCountryQuery::create()
      ->select(iceModelGeoCountryPeer::ISO3166)
      ->find()->getArrayCopy();

    parent::configure($options, $attributes);
  }

}

