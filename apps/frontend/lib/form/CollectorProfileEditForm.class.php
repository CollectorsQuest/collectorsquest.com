<?php

class CollectorProfileEditForm extends BaseCollectorProfileForm
{
  public function configure()
  {
    $years = array_combine(range(date('Y') - 100, date('Y')),
      range(date('Y') - 100, date('Y')));

    $this->setWidgets(array(
      'collector_type'  => new sfWidgetFormChoice(array(
          'choices'     => $this->getCollectorTypeChoices(),
          'expanded'    => true,
          'label'       => 'What type of collector are you?',
        ), array(
          'required'    => 'required',
      )),
      'birthday'        => new sfWidgetFormDate(array(
          'years'       => $years,
          'format'      => '%month% %day% %year%',
          'empty_values'=> array(
              'day'     => 'Day',
              'month'   => 'Month',
              'year'    => 'Year',
          ),
      )),
      'gender'          => new sfWidgetFormSelect(array(
          'choices' => array('' => 'Rather not say', 'f' => 'Female', 'm' => 'Male'),
      )),
      'zip_postal'      => new sfWidgetFormInputText(array(
          'label'       => 'Zip/Postal code',
      )),
      'country_iso3166'         => new cqWidgetFormI18nChoiceIceModelGeoCountry(array(
          'add_empty'     => true,
        ), array(
          'required'    => 'required',
      )),
      'website'         => new sfWidgetFormInputText(),

      'about_me'                  => new sfWidgetFormTextarea(),
      'about_collections'         => new sfWidgetFormTextarea(),
      'about_what_you_collect'    => new cqWidgetFormInputTags(array(
          'autocompleteURL'       => '@ajax_typeahead?section=tags&page=edit',
          'label'                 => 'What do you collect?',
          'additionalListClass'   => 'input-xxlarge',
        ), array(
          'placeholder'           => 'Tell us what kind of things you collect (separated by commas)'
      )),
      'about_what_you_sell'     => new cqWidgetFormInputTags(array(
        'autocompleteURL'       => '@ajax_typeahead?section=tags&page=edit',
        'label'                 => 'What do you sell?',
        'additionalListClass'   => 'input-xxlarge',
      ), array(
        'placeholder'           => 'Tell us what kind of things you sell (separated by commas)'
      )),
      'about_purchases_per_year'  => new sfWidgetFormInputText(array(
          'label'                 => 'How many times a year do you purchase?',
        ),array(
          'placeholder'           => 'Share the number of purchases you make annually. Numeric value please',
      )),
      'about_most_expensive_item' => new bsWidgetFormInputTextAppendPrepend(array(
          'label'                 => 'What is the most you ever spent on an item? (in USD)',
          'prepend'                => '$',
        ), array(
      )),
      'about_annually_spend'      => new bsWidgetFormInputTextAppendPrepend(array(
          'label'                 => 'How much do you spend annually? (in USD)',
          'prepend'                => '$',
        ), array(
      )),
      'about_new_item_every'      => new sfWidgetFormInputText(array(
          'label'                 => 'How much time is there between your purchases?',
      )),
      'about_interests'           => new sfWidgetFormTextarea()
    ));

    $this->setValidators(array(
      'collector_type' => new sfValidatorChoice(array('choices' => array_keys($this->getCollectorTypeChoices()), 'required' => true), array(
          'required'   => 'You must select the type of collector you are.'
      )),

      'birthday' => new sfValidatorDate(array('required' => false)),
      'gender' => new sfValidatorChoice(array('choices' => array('f', 'm'), 'required' => false)),
      'zip_postal' => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'country_iso3166' => new sfValidatorPropelChoice(
        array('model' => 'iceModelGeoCountry', 'column' => 'iso3166',)
      ),
      'website' => new sfValidatorString(array('max_length' => 128, 'required' => false)),

      'about_me' => new sfValidatorString(array('required' => false)),
      'about_collections' => new sfValidatorString(array('required' => false)),
      'about_what_you_collect' => new cqValidatorTags(array('required' => false)),
      'about_what_you_sell' => new cqValidatorTags(array('required' => false)),
      'about_purchases_per_year'   => new sfValidatorNumber(array('required' => false, 'min' => 0), array(
          'min' => 'You cannot enter a value below zero here.',
      )),
      'about_most_expensive_item' => new cqValidatorPrice(array('required' => false)),
      'about_annually_spend' => new cqValidatorPrice(array('required' => false)),
      'about_new_item_every' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'about_interests' => new sfValidatorString(array('required' => false)),
    ));

  }

  public function getCollectorTypeChoices()
  {
    return array(
      CollectorProfilePeer::COLLECTOR_TYPE_CASUAL => 'Casual',
      CollectorProfilePeer::COLLECTOR_TYPE_OCCASIONAL => 'Occasional',
      CollectorProfilePeer::COLLECTOR_TYPE_SERIOUS => 'Serious',
      CollectorProfilePeer::COLLECTOR_TYPE_OBSESSIVE => 'Obsessive',
      CollectorProfilePeer::COLLECTOR_TYPE_EXPERT => 'Expert',
    );
  }

}
