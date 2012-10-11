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

      // Retrieve the Object from the database
      $object = call_user_func_array(
        array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id)
      );

      if ($object && is_callable(array($object, 'set'. sfInflector::camelize($field))))
      {
        if ($this->getUser()->isOwnerOf($object))
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

      // Retrieve the Object from the database
      $object = call_user_func_array(
        array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id)
      );

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new FeedbackForm();
    $this->form->setDefault('page', $request->getParameter('page', $request->getReferer()));

    if ($this->getUser()->isAuthenticated())
    {
      $this->form->setDefault('fullname', $this->getCollector()->getUsername());
      $this->form->setDefault('email', $this->getCollector()->getEmail());
    }

    if ($request->isMethod('post'))
    {
      $sent = false;

      $this->form->bind($request->getParameter('feedback'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

        $cqEmail = new cqEmail($this->getMailer());
        $sent = $cqEmail->send('internal/feedback', array(
            'to' => 'info@collectorsquest.com',
            'subject' => '[Feedback] '. $values['fullname'],
            'params' => array(
              'feedback' => array(
                'fullname' => $values['fullname'],
                'email' => $values['email'],
                'message' => nl2br($values['message']),
                'page' => urldecode($values['page'])
              ),
              'browser' => array(
                'f_ip_address' => cqStatic::getUserIpAddress(),
                'f_javascript_enabled' => $values['f_javascript_enabled'],
                'f_browser_type' => $values['f_browser_type'],
                'f_browser_version' => $values['f_browser_version'],
                'f_browser_color_depth' => $values['f_browser_color_depth'],
                'f_resolution' => $values['f_resolution'],
                'f_browser_size' => $values['f_browser_size']
              ),
            ),
        ));

        if ($values['send_copy'])
        {
          $cqEmail = new cqEmail($this->getMailer());
          $sent = $cqEmail->send('internal/feedback_copy', array(
              'to' => $values['email'],
              'subject' => '[Feedback] '. $values['fullname'],
              'params' => array(
                'feedback' => array(
                'fullname' => $values['fullname'],
                'email' => $values['email'],
                'message' => nl2br($values['message']),
                'page' => urldecode($values['page'])
              ),
            ),
          ));
        }
      }

      if ($sent)
      {
        $this->getUser()->setFlash(
          'success',
          $this->__(
            'Thank you for the feedback. If needed, we will get in
             touch with you within the next business day.',
            array(), 'flash'
          ),
          'ajax'
        );

        $this->setTemplate('successFeedback');
      }
      else
      {
        $this->getUser()->setFlash(
          'error',
          $this->__('There are errors in the fields or some are left empty.', array(), 'flash')
        );
      }
    }
  }

  public function executeMultimedia(sfWebRequest $request)
  {
    if (!$mutlimedia = iceModelMultimediaQuery::create()->findOneById($request->getParameter('id')))
    {
      return sfView::NONE;
    }

    $this->multimedia = $mutlimedia;
    $this->which = $request->getParameter('which', 'original');

    return sfView::SUCCESS;
  }

  public function executeMailChimpWebhook(sfWebRequest $request)
  {
    // Simple protection against random requests to the webhook
    $this->forward404Unless($request->getParameter('key') === substr(cqConfig::getCredentials('mailchimp'), 0, 7));

    /** @var $data array */
    $data = $request->getParameter('data');

    /** @var $collector Collector */
    $collector = CollectorQuery::create()->findOneByEmail($data['email']);

    // We need a collector to continue
    $this->forward404Unless($collector instanceof Collector);

    switch ($request->getParameter('type'))
    {
      case 'unsubscribe':
      case 'cleaned':

        // "CollectorsQuest.com Users" list
        if ($data['list_id'] == '4b51c2b29c')
        {
          $collector->setPreferencesNewsletter(false);
          $collector->save();
        }

        break;
      case 'profile':

        /** @var $merges array */
        $merges = $data['merges'];

        $collector->setPreferencesNewsletter($merges['NEWSLETTER'] === 'No' ? false : true);
        $collector->setUserType(
          $merges['TYPE'] == 'Seller' ? CollectorPeer::TYPE_SELLER : CollectorPeer::TYPE_COLLECTOR
        );
        $collector->save();

        break;
    }

    return sfView::NONE;
  }

}
