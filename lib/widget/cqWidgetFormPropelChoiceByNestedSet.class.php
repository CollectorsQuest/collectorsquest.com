<?php

/**
 * cqWidgetFormPropelChoiceByNestedSet
 *
 * Does not work with scoping!
 */
class cqWidgetFormPropelChoiceByNestedSet extends sfWidgetFormPropelChoice
{

  /**
   * @see sfWidget
   */
  public function __construct($options = array(), $attributes = array())
  {
    $options['choices'] = array();

    parent::__construct($options, $attributes);
  }

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * model:       The model class (required)
   *  * add_empty:   Whether to add a first empty value or not (false by default)
   *                 If the option is not a Boolean, the value will be used as the text value
   *  * method:      The method to use to display object values (__toString by default)
   *  * key_method:  The method to use to display the object keys (getPrimaryKey by default)
   *  * query_methods: An array of method names listing the methods to execute
   *                 on the model's query object
   *  * spacing_character: The character(s) to be prepended to the object
   *                 string representation. Defaults to 4 non-breaking spaces
   *  * criteria:    A criteria to use when retrieving objects
   *  * connection:  The Propel connection to use (null by default)
   *  * chozen:      Should we enable the jquery chozen interactive functionality
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('add_empty', false);
    $this->addOption('method', '__toString');
    $this->addOption('key_method', 'getPrimaryKey');
    $this->addOption('query_methods', array());
    $this->addOption('spacing_character', '&nbsp;&nbsp;&nbsp;&nbsp;');
    $this->addOption('criteria', null);
    $this->addOption('connection', null);
    $this->addOption('chozen', false);

    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('chozen') === true)
    {
      if (isset($attributes['class']) && $attributes['class'])
      {
        $attributes['class'] .= ' chzn-select js-hide';
      }
      else
      {
        $attributes['class'] = 'chzn-select js-hide';
      }

      $options = '{}';
      if ($this->getOption('add_empty') === true)
      {
        $options = '{ allow_single_deselect: true }';
      }

      return parent::render($name, $value, $attributes, $errors)
        . sprintf(<<<EOF

<script type="text/javascript">
$(document).ready(function()
{
  $(".chzn-select").chosen(%s);
});
</script>

EOF
        ,
        $options
      );
    }
    else
    {
      return parent::render($name, $value, $attributes, $errors);
    }

  }

  /**
   * Returns the choices associated to the model.
   *
   * @throws RuntimeException
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
    if (false !== $this->getOption('add_empty'))
    {
      $choices[''] = true === $this->getOption('add_empty') ? '' : $this->getOption('add_empty');
    }

    $criteria = PropelQuery::from($this->getOption('model'));
    if ($this->getOption('criteria'))
    {
      $criteria->mergeWith($this->getOption('criteria'));
    }
    foreach ($this->getOption('query_methods') as $methodName => $methodParams)
    {
      if (is_array($methodParams))
      {
        call_user_func_array(array($criteria, $methodName), $methodParams);
      }
      else
      {
        $criteria->$methodParams();
      }
    }
    // we currently do not handle scoped trees
    $objects = $criteria->findTree($this->getOption('connection'));

    $methodKey = $this->getOption('key_method');
    if (!method_exists($this->getOption('model'), $methodKey))
    {
      throw new RuntimeException(sprintf(
        'Class "%s" must implement a "%s" method to be rendered in a "%s" widget',
        $this->getOption('model'), $methodKey, __CLASS__
      ));
    }

    $methodValue = $this->getOption('method');
    if (!method_exists($this->getOption('model'), $methodValue))
    {
      throw new RuntimeException(sprintf(
        'Class "%s" must implement a "%s" method to be rendered in a "%s" widget',
        $this->getOption('model'), $methodValue, __CLASS__
      ));
    }

    foreach ($objects as $object)
    {
      if ($this->getOption('add_empty') === true && $object->getLevel() === 0)
      {
        continue;
      }

      if ($this->getOption('chozen') !== true)
      {
        $choices[$object->$methodKey()] = $object->$methodValue();
      }
      else
      {
        $choices[$object->$methodKey()] = str_repeat(
          $this->getOption('spacing_character'), $object->getLevel() - 1
        ) .' '. $object->getLevel() .'. '. $object->$methodValue();
      }
    }

    return $choices;
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return ($this->getOption('chozen') === true) ?
      array('/assets/css/jquery/chosen.css' => 'all') : array();
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return ($this->getOption('chozen') === true) ?
      array('/assets/js/jquery/chosen.js') : array();
  }

}
