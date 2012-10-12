<?php

class cqWidgetFormRatingStar extends sfWidgetFormSelectRadio
{

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('class', 'rating');
  }

  protected function formatChoices($name, $value, $choices, $attributes)
  {
    $inputs = array();

    foreach ($choices as $key => $option)
    {
      $baseAttributes = array(
        'name'  => substr($name, 0, -2),
        'type'  => 'radio',
        'value' => self::escapeOnce($key),
        'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
        'class' => 'cq_star_rating_input'
      );

      $labelAttributes = array();

      if (strval($key) == strval($value === false ? 0 : $value))
      {
        $baseAttributes['checked'] = 'checked';
        $labelAttributes['class'] = 'checked';
      }

      $inputs[$id] = array(
        'label' => $this->renderContentTag(
          'label', '☆'.$this->renderTag('input', array_merge($baseAttributes, $attributes)), $labelAttributes
        ),
      );
    }

    return $this->formatter($this, $inputs);
  }

  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $rows[] =  $input['label'];
    }
    $rows = array_reverse($rows);
    return !$rows ? '' : $this->renderContentTag(
      'div',
      implode($this->getOption('separator'), $rows),
      array('class' => $this->getOption('class'))
    ).$this->getJavaScript();
  }

  private function getJavaScript()
  {
    return sprintf(<<<EOF
<script type="text/javascript">
  if (!window.initCqRatableStars)
  {
    function initCqRatableStars(){
      $('.cq_star_rating_input').live('change', function() {
        var container = $(this).closest('.%s');
        $('.cq_star_rating_input', container).each(function(){
          $(this).parent('label').removeClass('checked');
        });
        $('.cq_star_rating_input:checked', container).parent('label').addClass('checked');
      });
    }
    $(document).ready(function() {
      initCqRatableStars();
      });
  }
</script>
EOF
      , $this->getOption('class')
      );
  }
}
