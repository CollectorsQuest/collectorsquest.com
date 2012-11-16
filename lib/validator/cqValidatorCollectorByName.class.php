
<?php

/**
 * Description of cqValidatorPrivateMessageReceiver
 */
class cqValidatorCollectorByName extends sfValidatorString
{

  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * return_object: Whether to return the collector object instead of its ID
   *  * invalid_ids:   Array of forbidden IDs
   *
   * Available error codes:
   *
   *  * collector_not_found
   *  * collector_invalid_id
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see   sfValidatorString
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('return_object', false);
    $this->addOption('invalid_ids', array());

    $this->addMessage('collecotor_not_found',
      'No collector matching "%value%" was found');
    $this->addMessage('collector_ambiguous',
      'More than one collector matching "%value%" was found');
    $this->addMessage('collector_invalid_id',
      'The "%value%" collector is disallowed for this field');
  }

  public function doClean($value)
  {
    $value = parent::doClean($value);
    $collectors = CollectorQuery::create()
      ->filterByUsername($value)
      ->find();

    if (!$collectors->count())
    {
      $collectors = CollectorQuery::create()
        ->filterByDisplayName($value)
        ->find();
    }

    if (count($collectors) > 1)
    {
      throw new sfValidatorError($this, 'collector_ambiguous', array(
          'value' => $value,
      ));
    }

    $collector = $collectors->getFirst();
    if (!$collector)
    {
      throw new sfValidatorError($this, 'collecotor_not_found', array(
          'value' => $value,
      ));
    }

    if (in_array($collector->getId(), (array) $this->getOption('invalid_ids')) )
    {
      throw new sfValidatorError($this, 'collector_invalid_id', array(
          'value' => $value,
      ));
    }

    if ($this->getOption('return_object'))
    {
      return $collector;
    }
    else
    {
      return $collector->getId();
    }
  }

}

