<?php

class cqWidgetFormInputTags extends sfWidgetFormInputText
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

    $this->addRequiredOption('autocompleteURL');
    $this->addOption('additionalListClass');
    $this->addOption('animSpeed');
    $this->addOption('autocompleteOptions', array('minLength' => 3));
    // return, comma, semicolon
    $this->addOption('breakKeyCodes', array(13, 44, 59));

    $this->addOption('explode_separator', ',');
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
    if ('[]' != substr($name, -2))
    {
      $name .= '[]';
    }

    if (!is_array($values))
    {
      $values = explode($this->getOption('explode_separator'), $values);
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

    $tabindex = isset($attributes['tabindex']) ? $attributes['tabindex'] : 0;
    unset($attributes['tabindex']);

    return $this->renderContentTag('div', implode("\n", $tags), array(
      'id' => $this->generateId($name).'_holder',
    ), $attributes). sprintf(<<<EOF

<script>
$(document).ready(function() {
  $('#%s input').attr('tabIndex', 0).tagedit({
    autocompleteURL: '%s',
    autocompleteOptions: %s,
    breakKeyCodes: [ %s ],
    additionalListClass: '%s',
    animSpeed: %d,
  });

  $('#%s').attr('tabIndex', %d).on('focus', function() {
    $(this).find('ul').click();
  });
});
</script>

EOF
      ,
      $this->generateId($name).'_holder',
      sfContext::getInstance()->getController()->genUrl($this->getOption('autocompleteURL'), true),
      json_encode($this->getOption('autocompleteOptions')),
      implode(', ', $this->getOption('breakKeyCodes')),
      $this->getOption('additionalListClass'),
      $this->getOption('animSpeed'),
      $this->generateId($name).'_holder',
      $tabindex
    );
  }

}
