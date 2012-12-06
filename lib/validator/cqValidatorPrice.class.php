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
    $this->setOption('empty_value', '');

    $this->addOption('min', 0);
    $this->addOption('max', 1000000);
    $this->addOption('scale', 2);
    $this->addOption('integer', false);

    $this->addMessage('max', '"%value%" must be at most %max%.');
    $this->addMessage('min', '"%value%" must be at least %min%.');
    $this->addMessage('invalid', 'The price amount you have specified is not valid');
    $this->addMessage('required', 'The price amount is required');
  }

  protected function doClean($value)
  {
    $clean = str_ireplace(array('o', 'о'), '0', (string) $value);
    $price = cqStatic::floatval($clean, $this->getOption('scale'));

    if ($this->getOption('integer') == true)
    {
      $price = (int) $price;
    }

    if ($this->hasOption('max') && $price > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
    }

    if ($this->hasOption('min') && $price < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => $this->getOption('min')));
    }

    return $price;
  }
}
