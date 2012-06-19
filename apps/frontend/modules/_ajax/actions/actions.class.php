<?php

class _ajaxActions extends cqFrontendActions
{
  public function executeEditable(sfWebRequest $request)
  {
    @list($model, $id, $field) = explode('_', $request->getParameter('id'));
    $value = $request->getParameter('value');

    if ($model && is_callable(array(sfInflector::camelize($model).'Peer', 'retrieveByPk')))
    {
      // Cast the id to INT for sanity
      $id = (int) $id;

      $object = call_user_func_array(array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id));
      if ($object && is_callable(array($object, 'set'. sfInflector::camelize($field))))
      {
        if ($this->getCollector()->isOwnerOf($object) || true)
        {
          call_user_func_array(array($object, 'set'. sfInflector::camelize($field)), array($value));
          $object->save();
        }
      }

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

  public function executeEditableLoad(sfWebRequest $request)
  {
    @list($model, $id, $field) = explode('_', $request->getParameter('id'));
    $value = '';

    if ($model && is_callable(array(sfInflector::camelize($model).'Peer', 'retrieveByPk')))
    {
      // Cast the id to INT for sanity
      $id = (int) $id;

      $object = call_user_func_array(array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id));

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        if (in_array($field, array('name', 'description')))
        {
          $value = call_user_func_array(array($object, 'get'. sfInflector::camelize($field)), array('markdown'));
        }
        else
        {
          $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
        }
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

}
