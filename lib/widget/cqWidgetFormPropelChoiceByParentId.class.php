<?php

/**
 * cqWidgetFormPropelChoiceByParentId
 */
class cqWidgetFormPropelChoiceByParentId extends sfWidgetFormPropelChoice
{

  /**
   * @param     array $options
   * @param     array $attributes
   *
   * @see       sfWidgetFormPropelChoice::configure()
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('parent_id_method', 'getParentId');
    $this->addOption('id_to_make_first', null);
    $this->addOption('spacing_character', '&nbsp;&nbsp;&nbsp;&nbsp;');

    parent::configure($options, $attributes);
  }

  public function getChoices()
  {
    $choices = array();

    $class = constant($this->getOption('model').'::PEER');

    $criteria = null === $this->getOption('criteria') ? new Criteria() : clone $this->getOption('criteria');
    if ($order = $this->getOption('order_by'))
    {
      $method = sprintf('add%sOrderByColumn', 0 === strpos(strtoupper($order[1]), 'ASC') ? 'Ascending' : 'Descending');
      $criteria->$method(call_user_func(array($class, 'translateFieldName'), $order[0], BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME));
    }
    $objects = call_user_func(array($class, $this->getOption('peer_method')), $criteria, $this->getOption('connection'));

    $methodKey = $this->getOption('key_method');
    if (!method_exists($this->getOption('model' ), $methodKey))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodKey, __CLASS__));
    }

    $methodValue = $this->getOption('method');
    if (!method_exists($this->getOption('model'), $methodValue))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodValue, __CLASS__));
    }

    $methodParentId = $this->getOption('parent_id_method');
    if (!method_exists($this->getOption('model'), $methodParentId))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodParentId, __CLASS__));
    }

    foreach ($objects as $object)
    {
      $choices[] = array(
          'id' => $object->$methodKey(),
          'parent_id' =>$object->$methodParentId(),
          'value' =>$object->$methodValue()
      );
    }

    // build a tree like structure for the select field
    $choices = $this->orderChoicesInTree($choices);

    $id_to_make_first = $this->getOption('id_to_make_first');
    if (null !== $id_to_make_first && isset($choices[$id_to_make_first]))
    {
      $value = $choices[$id_to_make_first];
      unset($choices[$id_to_make_first]);
      $choices = array($id_to_make_first => $value) + $choices;
    }

    if (false !== $this->getOption('add_empty'))
    {
      $choices = array('' => true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty')))
        + $choices;
    }

    return $choices;
  }

  protected function orderChoicesInTree($choices, $parent_id = 0, $depth = 0, $runs = 0)
  {
    $result = array();
    foreach ($choices as $choice)
    {
      if ($choice['id'] == $choice['parent_id'])
      {
        // self referencing choices are roots; they should be added to the result
        // only at depth 0
        if (0 == $depth)
        {
          $result[$choice['id']] = str_repeat($this->getOption('spacing_character'), $depth) . $choice['value'];
        }
      }
      else if ($parent_id == $choice['parent_id'])
      {
        // if we are not dealing with a self-referencing choice and it has the parent
        // we are looking for, add it to the result
        $result[$choice['id']] = str_repeat($this->getOption('spacing_character'), $depth) . $choice['value'];
        // and try to build subtree with the current choice as parent
        $result += $this->orderChoicesInTree($choices, $choice['id'], $depth+1, ++$runs);
      }
    }

    return $result;
  }

}
