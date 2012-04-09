<?php

/**
 * cqWidgetFormInputCentsToUsd
 */
class cqWidgetFormInputCentsToUsd extends sfWidgetFormInputText
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // when there are errors the validator that turns dolars to cents will also fail
    // so we should convert the value from dollars to cents only when there are no errors
    // otherwize display it as is
    if (!count($errors) && !(isset($attributes['convert']) && false != $attributes['convert']))
    {
      $value = $value / 100;
      unset ($attributes['convert']);
    }

    return parent::render($name, $value, $attributes, $errors);
  }

}
