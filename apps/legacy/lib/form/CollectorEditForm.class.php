<?php

/**
 * @method  Collector  getObject()
 */
class CollectorEditForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'photo'          => new sfWidgetFormInputFile(),
      'display_name'   => new sfWidgetFormInputText(),
      'email'          => new sfWidgetFormInputText(),
      'password'       => new sfWidgetFormInputPassword(),
      'password_again' => new sfWidgetFormInputPassword()
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'Collector', 'column' => 'id', 'required' => true)),
      'photo'          => new sfValidatorFile(array('required' => false, 'mime_categories' => 'web_images')),
      'display_name'   => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'email'          => new sfValidatorEmail(array('required' => true)),
      'password'       => new sfValidatorString(array('min_length' => 6, 'max_length' => 50, 'required' => false)),
      'password_again' => new sfValidatorPass()
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        array('throw_global_error' => true),
        array('invalid' => 'The two passwords do not match, please enter them again!')
      )
    );

    $profile = new CollectorProfileEditForm($this->getObject()->getProfile());
    $this->embedForm('profile', $profile);

    $this->widgetSchema->setNameFormat('collector[%s]');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collector';
  }

  public function updateEmailColumn($newEmail)
  {
    /* @var $collector Collector */
    $collector = $this->getObject();
    $oldEmail = $collector->getEmail();

    if ($newEmail != $oldEmail)
    {
      $collectorEmail = CollectorEmailPeer::retrieveByCollectorEmail($collector, $newEmail);

      if (!$collectorEmail)
      {
        CollectorEmailQuery::create()
          ->filterByCollector($collector)
          ->filterByIsVerified(false)
          ->delete();

        // genearte a salt to use in collector_email
        $salt = $collector->generateSalt();

        $collectorEmail = new CollectorEmail();
        $collectorEmail->setCollector($collector);
        $collectorEmail->setEmail($newEmail);
        $collectorEmail->setSalt($salt);
        $collectorEmail->setHash($collector->getAutoLoginHash(null, null, $salt));
        $collectorEmail->setIsVerified(false);
        $collectorEmail->save();

        $this->setOption('newEmail', $collectorEmail);

        return $oldEmail;
      }
      else if (!$collectorEmail->getIsVerified())
      {
        $this->setOption('newEmail', $collectorEmail);

        return $oldEmail;
      }
      else
      {
        $collector->setEmail($newEmail);

        return $newEmail;
      }
    }

    return $newEmail;
  }

}

