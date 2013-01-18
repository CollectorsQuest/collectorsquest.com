<?php

class IsPublicForm extends IsPublicDynamicExtendForm
{
  public function configure()
  {
    $this->useFields(array('is_public'));
    $this->widgetSchema['is_public'] = new sfWidgetFormChoice(
      array('choices' => array('1' => 'Public', '0' => 'Private'), 'expanded' => true,
        'label' => 'Make this item:'));
    unset($this['id']);

  }

}
