<?php

class sfWidgetFormChosenChoice extends sfWidgetFormChoice
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (isset($attributes['class']) && $attributes['class'])
    {
      $attributes['class'] .= ' chzn-select js-hide';
    }
    else
    {
      $attributes['class'] = 'chzn-select js-hide';
    }

    return parent::render($name, $value, $attributes, $errors)
      . <<<EOF

<script type="text/javascript">
$(document).ready(function()
{
  $(".chzn-select").chosen();
});
</script>

EOF;
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/assets/css/jquery/chosen.css' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/assets/js/jquery/chosen.js');
  }

}
