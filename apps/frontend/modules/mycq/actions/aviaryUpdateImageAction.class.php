<?php

class aviaryUpdateImageAction extends sfAction
{
  public function execute($request)
  {
    sfConfig::set('sf_web_debug', false);

    if (
      sfRequest::POST == $request->getMethod() && $request->hasParameter('url') &&
      $request->hasParameter('postdata')
    ) {
      $postdata = urldecode($request->getParameter('postdata'));
      $url = urldecode($request->getParameter('url'));

      $message_data = $this->getUser()->hmacVerifyMessage(
        $postdata, '+ 20 days', cqConfig::getCredentials('aviary', 'hmac_secret')
      );

      if (false !== $message_data)
      {
        $message = json_decode($message_data, true);
      }
      else
      {
        $this->getResponse()->setStatusCode(401);

        $this->getLogger()->log(json_encode($postdata));

        return $this->renderText($postdata);
      }

      if (
        isset($message['multimedia-id']) &&
        $image = iceModelMultimediaPeer::retrieveByPK($message['multimedia-id'])
      ) {
        $image->setCreatedAt(time());
        $image->setName(basename($url));
        $image->setMd5(md5($url));
        $image->setSource($url);
        $image->createDirectory();

        if (copy($url, $image->getAbsolutePath('original')))
        {
          $image->save();

          $this->getResponse()->setStatusCode(200);
          $this->getResponse()->setHttpHeader('Content-Encoding', 'chunked');
          $this->getResponse()->setHttpHeader('Transfer-Encoding', 'chunked');
          $this->getResponse()->sendHttpHeaders();
          ignore_user_abort(true);
          flush();

          if (method_exists($image->getModelObject(), 'createMultimediaThumbs'))
          {
            $image->getModelObject()->createMultimediaThumbs($image);
          }

          return sfView::HEADER_ONLY;
        }
        else
        {
          $this->getResponse()->setStatusCode(500);

          return $this->renderText('Error copying image from url');
        }
      }
      else
      {
        $this->getResponse()->setStatusCode(403);

        return $this->renderText('Invalid multimedia id');
      }
    }

    $this->getResponse()->setStatusCode(400);

    return sfView::NONE;
  }
}
