<?php
/**
 * File: cqWidgetFormSelectPayment.class.php
 *
 * @author zecho
 * @version $Id$
 *
 */

class cqWidgetFormSelectPayment extends sfWidgetFormSelectRadio
{

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
      );

      if (strval($key) == strval($value === false ? 0 : $value))
      {
        $baseAttributes['checked'] = 'checked';
      }

      $inputs[$id] = array(
        'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
        'label' => $this->renderContentTag('label', $this->renderTag('img', array_merge($attributes, array(
          'src'=> self::escapeOnce($option),
          'alt'=> $key
        ))), array('for' => $id)),
      );
    }

    return call_user_func($this->getOption('formatter'), $this, $inputs);
  }

}
