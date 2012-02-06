<?php

/**
 * cqWidgetFormRange represents a number range widget.
 * 
 * @package    symfony
 * @subpackage widget
 * @author Yanko Simeonoff
 * @since $Date: 2011-06-24 00:17:39 +0300 (Fri, 24 Jun 2011) $
 * @version $Id: cqWidgetFormRange.class.php 2194 2011-06-23 21:17:39Z yanko $
 */
class cqWidgetFormRange extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * from_number:   The from number widget (required)
   *  * to_number:     The to number widget (required)
   *  * template:    The template to use to render the widget
   *                 Available placeholders: %from_number%, %to_number%
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('from');
    $this->addRequiredOption('to');

    $this->addOption('template', 'from %from_number% to %to_number%');
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The number displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $values = array_merge(array('from' => '', 'to' => '', 'is_empty' => ''), is_array($value) ? $value : array());

    return strtr($this->getOption('template'), array(
      '%from_number%' => $this->getOption('from')->render($name . '[from]', $value['from']),
      '%to_number%' => $this->getOption('to')->render($name . '[to]', $value['to']),
    ));
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array_unique(array_merge($this->getOption('from')->getStylesheets(), $this->getOption('to')->getStylesheets()));
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array_unique(array_merge($this->getOption('from')->getJavaScripts(), $this->getOption('to')->getJavaScripts()));
  }

}
