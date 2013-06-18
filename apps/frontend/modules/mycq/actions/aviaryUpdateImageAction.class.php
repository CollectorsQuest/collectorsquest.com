<?php

class aviaryUpdateImageAction extends sfAction
{

  public function execute($request)
  {
    sfConfig::set('sf_web_debug', false);

    /** @var $sf_response IceWebResponse */
    $sf_response = $this->getResponse();

    /** @var $sf_user cqFrontendUser */
    $sf_user = $this->getUser();

    if (
      sfRequest::POST == $request->getMethod() &&
      $request->hasParameter('url') && $request->hasParameter('postdata')
    )
    {
      $postdata = urldecode($request->getParameter('postdata'));
      $url = urldecode($request->getParameter('url'));

      $message_data = $sf_user->hmacVerifyMessage(
        $postdata, '+3 hours', cqConfig::getCredentials('aviary', 'hmac_secret')
      );

      if (false !== $message_data)
      {
        $message = json_decode($message_data, true);
      }
      else
      {
        $sf_response->setStatusCode(401);
        $this->getLogger()->log(json_encode($postdata));

        return $this->renderText($postdata);
      }

      if (
        isset($message['multimedia-id']) &&
        $image = iceModelMultimediaPeer::retrieveByPK($message['multimedia-id'])
      )
      {
        $image->setName(basename($url));
        $image->setMd5(md5($url));
        $image->setSource($url);
        $image->createDirectory();

        if (copy($url, $image->getAbsolutePath('original')))
        {
          $image->save();

          $sf_response->setStatusCode(200);
          $sf_response->setHttpHeader('Content-Encoding', 'chunked');
          $sf_response->setHttpHeader('Transfer-Encoding', 'chunked');
          $sf_response->sendHttpHeaders();

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
          $sf_response->setStatusCode(500);
          return $this->renderText('Error copying image from url');
        }
      }
      else
      {
        $sf_response->setStatusCode(403);

        return $this->renderText('Invalid multimedia id');
      }
    }

    $sf_response->setStatusCode(400);

    return sfView::NONE;
  }
}
