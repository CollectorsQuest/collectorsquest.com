<?php

/**
 * cqValidatorPrice validates price (with or without currency sign). It also converts the input value to a float.
 *
 * @package    symfony
 * @subpackage validator
 * @author     $Author$
 * @version    SVN: $Id: cqValidatorPrice.class.php 2135 2011-06-13 18:05:20Z yanko $
 */
class cqValidatorPrice extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max: The maximum value allowed
   *  * min: The minimum value allowed
   *
   * Available error codes:
   *
   *  * max
   *  * min
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('max', '"%value%" must be at most %max%.');
    $this->addMessage('min', '"%value%" must be at least %min%.');

    $this->addOption('min');
    $this->addOption('max');

    $this->setMessage('invalid', '"%value%" is not a number.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $value = preg_replace('/[^\d\.]+/', '', $value);
    
    if (!is_numeric($value))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    $clean = floatval($value);

    if ($this->hasOption('max') && $clean > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
    }

    if ($this->hasOption('min') && $clean < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => $this->getOption('min')));
    }

    return $clean;
  }
}
