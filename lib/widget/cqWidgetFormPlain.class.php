<?php
/**
 * cqWidgetFormPlain
 *
 * Provides means for rendering plain fields while retaining the value over saves
 */
class cqWidgetFormPlain extends sfWidgetForm
{

  /**
   * Constructor.
   *
   * Available options:
   *  * content_tag:      The content tag to be used when rendering the value,
   *                      defaults to '<pre>'
   *  * escape:           Whether to use output escaping on the value,
   *                      default is TRUE
   *  * render_callback:  Calback function if the value needs to be processed
   *                      before rendering
   *  * default_html:     What to display if the field is empty
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('content_tag', 'pre');
    $this->addOption('escape', true);
    $this->addOption('render_callback', null);
    // what will be displayed if value is empty
    $this->addOption('default_html', '<br />');
  }


  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_callable($this->getOption('render_callback')))
    {
      $rendered_value = call_user_func($this->getOption('render_callback'), $value);
    }
    else
    {
      $rendered_value = $value;
    }

    $hidden_input_tag = $this->renderTag(
      'input',
      array_merge(array(
          'type' => 'hidden',
          'name' => $name,
          'value' => $value
      ), $attributes));

    $display_html = $value
      ? $this->renderContentTag(
        $this->getOption('content_tag'),
        $this->getOption('escape')
          ? self::escapeOnce($rendered_value)
          : $rendered_value,
        $attributes)
      : $this->getOption('default_html');

    return $hidden_input_tag.$display_html;
  }

}
