<?php

/**
 * Validator counterpart to cqWidgetFormInputTags
 */
class cqValidatorTags extends sfValidatorBase
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('max', 'You can set at most %max% tags, you tried to set %count%,');
    $this->addMessage('min', 'You must set at least %min% tags, you tried to set %count%.');

    $this->addOption('min');
    $this->addOption('max');
  }

  protected function doClean($value)
  {
    $values = (array) $value;

    if (empty($values) && $this->getOption('required'))
    {
      throw new sfValidatorError($this, 'required');
    }

    $count = count($values);

    if ($this->hasOption('min') && $this->getOption('min') > $count)
    {
      throw new sfValidatorError($this, 'min', array(
          'min'   => $this->getOption('min'),
          'count' => $count,
      ));
    }

    if ($this->hasOption('max') && $this->getOption('max') < $count)
    {
      throw new sfValidatorError($this, 'max', array(
          'max'   => $this->getOption('max'),
          'count' => $count,
      ));
    }

    return $values;
  }

}