<?php

/**
 * cqWidgetFormInputCentsToUsd
 */
class cqWidgetFormInputCentsToUsd extends sfWidgetFormInputText
{

  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('show_zero_as_empty', false);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (0 == $value && $this->getOption('show_zero_as_empty'))
    {
      $value = '';
    }
    // when there are errors the validator that turns dolars to cents will also fail
    // so we should convert the value from dollars to cents only when there are no errors
    // otherwize display it as is
    else if (!count($errors) && !(isset($attributes['convert']) && false != $attributes['convert']))
    {
      $value = bcdiv($value, 100, 2);
      unset ($attributes['convert']);
    }

    return parent::render($name, $value, $attributes, $errors);
  }

}
