<?php

class emailsActions extends cqActions
{
  public function preExecute()
  {
    parent::preExecute();

    $collector = $this->getSampleCollector($this->getRequest());
    $collectible_for_sale = $this->getSampleCollectibleForSale($this->getRequest());
    $comment = $this->getSampleComment($this->getRequest());

    $this->emails = array(
      'Collectors' => array(
        'collector_password_reminder' => array(
          'name' => 'Password Reminder',
          'description' => '',
          'params' => array('collector' => $collector)
        )
      ),
      'Marketplace' => array(
        'seller_package_confirmation' => array(
          'name' => 'Package Confirmation',
          'description' => '',
          'params' => array('collector' => $collector, 'package_name' => '1 item only', 'package_items' => 1)
        ),
        'buyer_purchase_confirmation' => array(
          'name' => 'Buyer Purchase Confirmation',
          'description' => '',
          'params' => array(
            'buyer' => $collector, 'seller' => $collector,
            'collectible_for_sale' => $collectible_for_sale
          )
        ),
        'seller_purchase_confirmation' => array(
          'name' => 'Seller Purchase Confirmation',
          'description' => '',
          'params' => array(
            'seller' => $collector, 'buyer' => $collector,
            'collectible_for_sale' => $collectible_for_sale
          )
        )
      ),
      'Private Message' => array(
        'private_message_notification' => array(
          'name' => 'Private Message Notification',
          'description' => '',
          'params' => array('receiver' => $collector, 'sender' => $collector)
        )
      ),
      'Comments' => array(
        'comment_notification_owner' => array(
          'name' => 'Private Message Notification',
          'description' => '',
          'params' => array('collector' => $collector, 'author_name' => 'Jonny', 'comment' => $comment)
        )
      )
    );
  }

  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $email = $request->getParameter('email');

      foreach ($this->emails as $section => $emails)
      {
        if (array_key_exists($email['partial'], $emails))
        {
          $email['body'] = $this->getPartial('emails/'. $email['partial'], $emails[$email['partial']]['params']);
        }
      }

      if ($this->sendEmail($email['to'], $email['subject'], $email['body']))
      {
        $this->getUser()->setFlash('success', 'The test email was successfully sent! Check your inbox ;)');
      }
    }
    else
    {
      foreach ($this->emails as $section => $emails)
      {
        $key = $this->getRequestParameter('partial');
        if (array_key_exists($key, $emails))
        {
          sfConfig::set('sf_web_debug', false);

          return $this->renderPartial('emails/'. $key, $emails[$key]['params']);
        }
      }
    }

    $this->addBreadcrumb('Emails', 'emails/index');

    return sfView::SUCCESS;
  }

  private function getSampleCollector(sfWebRequest $request)
  {
    if ($request->getParameter('random'))
    {
      $collector = CollectorQuery::create()->addAscendingOrderByColumn('RAND()')->findOne();
    }
    else if (!$collector = CollectorPeer::retrieveByPK($request->getParameter('collector_id')))
    {
      $collector = new Collector();
      $collector->setDisplayName('Kiril Angov');
    }

    return $collector;
  }

  private function getSampleCollection(sfWebRequest $request)
  {
    if ($request->getParameter('random'))
    {
      $collection = CollectionQuery::create()->addAscendingOrderByColumn('RAND()')->findOne();
    }
    else if (!$collection = CollectionPeer::retrieveByPK($request->getParameter('collection_id')))
    {
      $collection = new Collection();
    }

    return $collection;
  }

  private function getSampleCollectible(sfWebRequest $request)
  {
    if ($request->getParameter('random'))
    {
      $collectible = CollectibleQuery::create()->addAscendingOrderByColumn('RAND()')->findOne();
    }
    else if (!$collectible = CollectiblePeer::retrieveByPK($request->getParameter('collectible_id')))
    {
      $collectible = new Collectible();
    }

    return $collectible;
  }

  private function getSampleCollectibleForSale(sfWebRequest $request)
  {
    if ($request->getParameter('random'))
    {
      $collectible_for_sale = CollectibleForSaleQuery::create()->addAscendingOrderByColumn('RAND()')->findOne();
    }
    else if (!$collectible_for_sale = CollectibleForSalePeer::retrieveByPK($request->getParameter('collectible_for_sale_id')))
    {
      $collectible_for_sale = new CollectibleForSale();
    }

    return $collectible_for_sale;
  }

  private function getSampleComment(sfWebRequest $request)
  {
    if ($request->getParameter('random'))
    {
      $comment = CommentQuery::create()->addAscendingOrderByColumn('RAND()')->findOne();
    }
    else if (!$comment = CommentPeer::retrieveByPK($request->getParameter('comment_id')))
    {
      $comment = new Comment();
    }

    return $comment;
  }
}
