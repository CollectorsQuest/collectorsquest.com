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
          'years'       => $years
      )),
      'gender'          => new sfWidgetFormSelect(array(
          'choices' => array('' => "Rather not say", 'f' => 'Female', 'm' => 'Male'),
      )),
      'zip_postal'      => new sfWidgetFormInputText(array(
          'label'       => 'Zip/Postal code',
      )),
      'country'         => new cqWidgetFormI18nChoiceCountry(array(
          'add_empty'     => true,
        ), array(
          'required'    => 'required',
      )),
      'website'         => new sfWidgetFormInputText(),

      'about_me'                  => new sfWidgetFormTextarea(),
      'about_collections'         => new sfWidgetFormTextarea(),
      'about_what_you_collect'    => new sfWidgetFormInputText(array(
          'label'                 => 'What do you collect?',
        ), array(
          'required'              => 'required',
      )),
      'about_purchase_per_year'   => new sfWidgetFormInputText(array(
          'label'                 => 'How many times a year do you purchase?',
        ),array(
          'required'              => 'required',
          'type'                  => 'number',
          'pattern'               => '\d+'
      )),
      'about_most_expensive_item' => new sfWidgetFormInputText(array(
          'label'                 => 'What is the most you ever spent on an item? (in USD)',
      )),
      'about_annually_spend'      => new sfWidgetFormInputText(array(
          'label'                 => 'How much do you spend annually? (in USD)',
      )),
      'about_new_item_every'      => new sfWidgetFormInputText(array(
          'label'                 => 'How much time is there between your purchases?',
      )),
      'about_interests'           => new sfWidgetFormTextarea()
    ));

    $this->setValidators(array(
      'collector_type' => new sfValidatorChoice(array('choices' => array_keys($this->getCollectorTypeChoices()), 'required' => true)),

      'birthday' => new sfValidatorDate(array('required' => false)),
      'gender' => new sfValidatorChoice(array('choices' => array('f', 'm'), 'required' => false)),
      'zip_postal' => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'country' => new sfValidatorI18nChoiceCountry(array('required' => false)),
      'website' => new sfValidatorString(array('max_length' => 128, 'required' => false)),

      'about_me' => new sfValidatorString(array('required' => false)),
      'about_collections' => new sfValidatorString(array('required' => false)),
      'about_what_you_collect' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'about_purchase_per_year'   => new sfValidatorNumber(array('required' => false)),
      'about_most_expensive_item' => new sfValidatorNumber(array('min' => 0, 'max' => 2147483647, 'required' => false)),
      'about_annually_spend' => new sfValidatorNumber(array('min' => 0, 'max' => 2147483647, 'required' => false)),
      'about_new_item_every' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'about_interests' => new sfValidatorString(array('required' => false)),
    ));

  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset($this['country_iso3166']);
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (( $profile = $this->getObject() ))
    {
      $this->setDefault('about_me', $profile->getAboutMe());
      $this->setDefault('about_collections', $profile->getAboutCollections());
      $this->setDefault('about_what_you_collect', $profile->getAboutWhatYouCollect());
      $this->setDefault('about_most_expensive_item', $profile->getAboutMostExpensiveItem());
      $this->setDefault('about_annually_spend', $profile->getAboutAnnuallySpend());
      $this->setDefault('about_new_item_every', $profile->getAboutNewItemEvery());
      $this->setDefault('about_interests', $profile->getAboutInterests());
      $this->setDefault('country', $profile->getCountryIso3166());
    }
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

  public function doUpdateObject($values = null)
  {
    parent::doUpdateObject($values);

    /** @var $profile CollectorProfile */
    $profile = $this->getObject();
    if (isset($values['country']))
    {
      $profile->setCountryIso3166($values['country']);
    }

    if (isset($values['about_me']))
    {
      $profile->setAboutMe($values['about_me']);
    }
    if (isset($values['about_collections']))
    {
      $profile->setAboutCollections($values['about_collections']);
    }
    if (isset($values['about_interests']))
    {
      $profile->setAboutInterests($values['about_interests']);
    }
    if (isset($values['about_what_you_collect']))
    {
      $profile->setAboutWhatYouCollect($values['about_what_you_collect']);
    }
    if (isset($values['about_most_expensive_item']))
    {
      $profile->setAboutMostExpensiveItem($values['about_most_expensive_item']);
    }
    if (isset($values['about_annually_spend']))
    {
      $profile->setAboutAnnuallySpend($values['about_annually_spend']);
    }
    if (isset($values['about_new_item_every']))
    {
      $profile->setAboutNewItemEvery($values['about_new_item_every']);
    }
  }

}
