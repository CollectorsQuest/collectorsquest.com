<?php

class ajaxActions extends cqBackendActions
{

  public function executeMultimediaReorder(sfWebRequest $request)
  {
    $items = $request->getParameter('items');
    $key   = $request->getParameter('key');
    parse_str($items, $order);

    if (is_array($order[$key]))
    foreach ($order[$key] as $position => $id)
    {
      $multimedia = iceModelMultimediaPeer::retrieveByPk($id);
      if ($multimedia && $multimedia->getPosition() != $position)
      {
        $multimedia->setIsPrimary($position == 0);
        $multimedia->setPosition($position);
        $multimedia->save();
      }
    }

    return sfView::NONE;
  }

  public function executeMultimediaDelete(sfWebRequest $request)
  {
    $id = $request->getParameter('id');

    if (ctype_digit($id))
    {
      $c = new Criteria();
      $c->add(iceModelMultimediaPeer::ID, $id);
      $c->setLimit(1);

      iceModelMultimediaPeer::doDelete($c);
    }

    return sfView::NONE;
  }

  public function executeMultimediaUpload(sfWebRequest $request)
  {
    $model    = $request->getParameter('model');
    $model_id = $request->getParameter('model_id');

    $object = call_user_func(array($model.'Peer', 'retrieveByPk'), $model_id);
    if ($object)
    {
      $files = $request->getFiles();
      file_put_contents('/tmp/filedata', serialize($files));
      if (isset($files['Filedata']))
      {
        $multimedia = iceModelMultimediaPeer::createMultimediaFromFile($object, $files['Filedata']);
        if ($multimedia)
        {
          $multimedia->makeThumb(150, 150, 'shave');
        }
      }
    }

    return sfView::NONE;
  }

}
