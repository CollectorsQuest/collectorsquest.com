<?php

/**
 * cqWidgetFormInputCentsToUsd
 */
class cqWidgetFormInputCentsToUsd extends sfWidgetFormInputText
{

  public function render($name, $value = null, $attributes = array(), $errors = array()) {
    $value = $value / 100;

    return parent::render($name, $value, $attributes, $errors);
  }
}
