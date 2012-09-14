<?php

/**
 * cqWidgetFormNullInput is used when you need an input which MUST NOT be rendered
 * to the template, but instead manually set through the form's defaults
 *
 */
class cqWidgetFormNullInput extends sfWidgetFormInput
{

  const NULL_RENDER = '<!-- A null field has been rendered here -->';

  /**
   * We return an HTML comment that denotes a null field has been rendered.
   *
   * @param     string $name
   * @param     array $value
   * @param     array $attributes
   * @param     array $errors
   *
   * @return    string
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return self::NULL_RENDER;
  }

}
