<?php

/**
 * Simple widget to render bootstrap prepend/append fields
 */
class bsWidgetFormInputTextAppendPrepend extends sfWidgetFormInputText
{
  /**
   * Options:
   *   prepend: the text to be prepended to the field
   *   append:  the text to be appended to the field
   *
   * @param     type $options
   * @param     type $attributes
   */
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('prepend');
    $this->addOption('append');

    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $prepend = $this->getOption('prepend')
      ? $this->renderContentTag('span', $this->getOption('prepend'), array('class' => 'add-on'))
      : '';

    $append = $this->getOption('append')
      ? $this->renderContentTag('span', $this->getOption('append'), array('class' => 'add-on'))
      : '';

    return $this->renderContentTag('div',
      $prepend.parent::render($name, $value, $attributes, $errors).$append,
      array(
        'class' => ($prepend ? 'input-prepend ' : '') . ($append ? 'input-append' : '')
    ));
  }

}