<?php

class cqWidgetFormMultipleInputText extends sfWidgetFormInputText
{
  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('separator', ',');
    $this->setOption('type', 'text');
  }

  /**
   * Renders the widget.
   *
   * @param  string $name          The element name
   * @param  string|array $values  The value displayed in this widget
   * @param  array  $attributes    An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors        An array of errors for the field
   *
   * @return string HTML tag(s) string
   *
   * @see sfWidgetForm
   */
  public function render($name, $values = null, $attributes = array(), $errors = array())
  {
    if (!is_array($values))
    {
      $values = explode($this->getOption('separator'), $values);
    }
    $values = array_pad($values, 1, '');

    $tags = array();
    foreach ($values as $value)
    {
      $tags[] = $this->renderTag(
        'input', array_merge(
          array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value),
          $attributes
        )
      );
    }

    return implode("\n", $tags);
  }
}
