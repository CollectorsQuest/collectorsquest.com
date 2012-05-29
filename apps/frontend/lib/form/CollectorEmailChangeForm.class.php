<?php

class CollectorEmailChangeForm extends BaseForm
{
  /** @var Collector */
  protected $collector;

  public function __construct(
    Collector $collector,
    $options = array(),
    $CSRFSecret = null
  ) {
    $this->collector = $collector;

    parent::__construct(array(), $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->setWidgets(array(
        'password' => new sfWidgetFormInputPassword(array(), array(
            'required' => 'required',
            'placeholder' => 'Your password',
        )),
        'email' => new sfWidgetFormInputText(array(), array(
            'type' => 'email',
            'required' => 'required',
            'placeholder' => 'Your new email address',
        )),
        'email_again' => new sfWidgetFormInputText(array(), array(
            'type' => 'email',
            'required' => 'required',
            'placeholder' => 'Enter your new address again',
        )),
    ));

    $this->setValidators(array(
        'password' => new sfValidatorString(),
        'email' => new sfValidatorAnd(array(
            new sfValidatorEmail(),
            new sfValidatorCallback(array(
                'callback' => array($this, 'validateEmail'),
            ),array(
                'invalid' => "This email is set for validation.
                              Check you old email's inbox",
            )),
          ), array('halt_on_error' => true)),
        'email_again' => new sfValidatorPass(),
    ));

    $this->mergePostValidator(new sfValidatorAnd(array(
        new CollectorEditFormPasswordSchemaValidator(null, array(
            'collector' => $this->collector,
            'old_password_field' => 'password',
        )),
        new sfValidatorSchemaCompare(
          'email', sfValidatorSchemaCompare::EQUAL, 'email_again',
          array('throw_global_error' => true),
          array('invalid' => 'The two emails do not match, please enter them again!')
        ),
    )));

    $this->widgetSchema->setNameFormat('collector_email[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function validateEmail($validator, $value, $arguments)
  {
    if ($this->collector->getEmail() == $value)
    {
      throw new sfValidatorError($validator,
        'This is the same email as your current one');
    }

    $collector = CollectorQuery::create()
      ->filterByEmail($value)
      ->findOne();
    if ($collector)
    {
      throw new sfValidatorError($validator, 'This email address is taken');
    }

    $collector = CollectorEmailQuery::create()
      ->filterByIsVerified(true)
      ->findOneByEmail($value);
    if ($collector)
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $value;
  }

  /**
   * Checks the form, and if valid returns a CollectorEmail object
   *
   * @param array $taintedValues
   * @param PropelPDO $con
   *
   * @return CollectorEmail|null
   */
  public function bindAndCreateCollectorEmail(
    array $taintedValues = null,
    PropelPDO $con = null
  ) {
    $this->bind($taintedValues);

    if ($this->isValid())
    {
      $new_email = $this->getValue('email');

      $collector_email = CollectorEmailPeer::retrieveByCollectorEmail(
        $this->collector, $new_email, null, $con);

      if (!$collector_email)
      {
        // remove unverified emails
        CollectorEmailQuery::create()
          ->filterByCollector($this->collector)
          ->filterByIsVerified(false)
          ->delete($con);

        // genearte a salt to use in collector_email
        $salt = $this->collector->generateSalt();

        $collector_email = new CollectorEmail();
        $collector_email->setCollector($this->collector);
        $collector_email->setEmail($new_email);
        $collector_email->setSalt($salt);
        $collector_email->setHash($this->collector->getAutoLoginHash(null, null, $salt));
        $collector_email->setIsVerified(false);
        $collector_email->save($con);
      }

      return $collector_email;
    }

    return null;
  }


}