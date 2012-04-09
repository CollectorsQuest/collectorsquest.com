<?php

/**
 * Description of cqValidatorPrivateMessageReceiver
 */
class cqValidatorCollectorByName extends sfValidatorString
{

  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('return_object', false);
    $this->addMessage('collecotor_not_found',
      'No collector matching "%value%" was found');
  }

  public function doClean($value)
  {
    $value = parent::doClean($value);

    $collector = CollectorQuery::create()
      ->filterByUsername($value)
      ->_or()
      ->filterByDisplayName($value)
      ->findOne();

    if (!$collector)
    {
      throw new sfValidatorError($this, 'collecotor_not_found', array(
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

