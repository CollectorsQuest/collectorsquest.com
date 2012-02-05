<?php

/**
 * marketplace actions.
 *
 * @package    collectorsquest
 * @subpackage marketplace
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class marketplaceActions extends cqActions
{
  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    $c = new Criteria;
//    $c->setDistinct();
    $c->addJoin(CollectionCategoryPeer::ID, CollectionPeer::COLLECTION_CATEGORY_ID, Criteria::INNER_JOIN);
    $c->addJoin(CollectionPeer::ID, CollectiblePeer::COLLECTION_ID, Criteria::INNER_JOIN);
    $c->addJoin(CollectiblePeer::ID, CollectibleForSalePeer::COLLECTIBLE_ID, Criteria::INNER_JOIN);
    $c->add(CollectibleForSalePeer::IS_SOLD, false);
    $c->add(CollectionCategoryPeer::NAME, 'None', Criteria::NOT_EQUAL);
    $c->addGroupByColumn(CollectionCategoryPeer::ID);
    $c->addAscendingOrderByColumn(CollectionCategoryPeer::NAME);

    $this->categories = CollectionCategoryPeer::doSelect($c);

    $this->categorySelected = null;
    if ($request->hasParameter('id'))
    {
      try
      {
        $this->categorySelected = $this->getRoute()->getObject();
      }
      catch (PropelException $e)
      {
        $this->redirect('@marketplace');
      }
    }

    $this->addBreadcrumb('Welcome to the Marketplace');

    return sfView::SUCCESS;
  }

  public function executeItemOffers()
  {
    $this->collectible_for_sale = $this->getRoute()->getObject();
    $this->forward404Unless($this->collectible_for_sale);

    $this->collectible = $this->collectible_for_sale->getCollectible();

    $this->addBreadcrumb('Your Marketplace', '@manage_marketplace');
    $this->addBreadcrumb('Collectibles for Sale', '@manage_marketplace');
    $this->addBreadcrumb($this->collectible->getName());

    if ($this->getUser()->isOwnerOf($this->collectible))
    {
      $c = new Criteria();
      $c->addAscendingOrderByColumn(CollectibleOfferPeer::COLLECTOR_ID);
      $c->addDescendingOrderByColumn(CollectibleOfferPeer::UPDATED_AT);
      $c->add(CollectibleOfferPeer::COLLECTIBLE_ID, $this->collectible->getId());

      $this->offers = CollectibleOfferPeer::doSelect($c);

      return 'Seller';
    }
    else
    {
      $c = new Criteria();
      $c->addDescendingOrderByColumn(CollectibleOfferPeer::UPDATED_AT);
      $c->add(CollectibleOfferPeer::COLLECTIBLE_ID, $this->collectible->getId());
      $c->add(CollectibleOfferPeer::COLLECTOR_ID, $this->getUser()->getCollector()->getId());

      $this->offers = CollectibleOfferPeer::doSelect($c);

      return 'Buyer';
    }
  }

  public function executeItemOffer()
  {
    /* @var $offer CollectibleOffer */
    $offer = $this->getRoute()->getObject();

    $collectible = $offer->getCollectible();
    $this->forward404Unless($this->getUser()->isOwnerOf($collectible) || $offer->getCollectorId() == $this->getUser()->getId());

    $cmd = $this->getRequestParameter('cmd');
    $this->forward404Unless($cmd);

    $this->loadHelpers(array('cqLinks'));

    if ($this->getUser()->isOwnerOf($collectible))
    {
      $who = 'seller';

      $seller = $this->getUser()->getCollector();
      $buyer = $offer->getCollector();
    }
    else
    {
      $who = 'buyer';

      $seller = $collectible->getCollection()->getCollector();
      $buyer = $this->getUser()->getCollector();
    }

    $replacements = array(
      '%seller%' => $seller->getDisplayName(),
      '%buyer%' => $buyer->getDisplayName(),
      '%collection_item%' => $collectible->getName(),
      '%price%' => money_format('%.2n', $offer->getPrice()),
      '%image%' => link_to_collectible($collectible, 'image', array('width' => 100, 'height' => 100, 'style' => 'border: 0px;'))
    );
    // New cqMail Added By Prakash Panchal. 7-APR-2011
    // get mail content according to name
    // $mail = class intiated
    $ocqMail = new cqMail();

    switch ($cmd)
    {
      case 'accept':
        $offer->setStatus('accepted');
        $offer->save();

        /* @var $collectibleForSale CollectibleForSale */
        $collectibleForSale = $offer->getCollectibleForSale();

        $collectibleForSale->setQuantity($collectibleForSale->getQuantity()-1);
        $collectibleForSale->setIsSold($collectibleForSale->getQuantity() == 0);
        $collectibleForSale->save();

        if ($who == 'seller')
        {
          // Send mail to SELLER Added By Prakash Panchal 8-APR-2011
          $ssMailSubject = "You sold one of your items at the CQ Marketplace!";
          $ocqMail->setTemplate('marketplace_offer_accepted_seller.html');

          $replacements['%contact_link%'] = link_to('Please contact the buyer now with your payment details', '@message_compose?to=' . $buyer->getId() . '&subject=Regarding item ' . urlencode($collectible->getName()), array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $this->sendEmail($seller->getEmail(), $ssMailSubject, $asMailContent);

          // Send mail to BUYER Added By Prakash Panchal 8-APR-2011
          $ssMailSubject = "Your offer was accepted at the CQ Marketplace!";
          $ocqMail->setTemplate('marketplace_offer_accepted_buyer.html');

          $replacements['%contact_link%'] = link_to('contact the seller now with any questions you may have', '@message_compose?to=' . $seller->getId() . '&subject=Regarding item ' . urlencode($collectible->getName()), array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $this->sendEmail($buyer->getEmail(), $ssMailSubject, $asMailContent);
        }
        else
        {
          // New Mail Code Added By Prakash Panchal 8-APR-2011
          $ssMailSubject = "Your offer was accepted at the CQ Marketplace!";
          $ocqMail->setTemplate('marketplace_offer_accepted_seller.html');

          $replacements['%contact_link%'] = link_to('contact the buyer now with payment details', '@message_compose?to=' . $buyer->getId() . '&subject=Regarding item ' . urlencode($collectible->getName()), array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $bSendMail = $this->sendEmail($seller->getEmail(), $ssMailSubject, $asMailContent);

          // New Mail Code Added By Prakash Panchal 8-APR-2011
          $ssMailSubject = "Your bought an item at the CQ Marketplace!";
          $ocqMail->setTemplate('marketplace_offer_accepted_buyer.html');

          $replacements['%contact_link%'] = link_to('contact the seller now with any questions you may have.', '@message_compose?to=' . $seller->getId() . '&subject=Regarding item ' . urlencode($collectible->getName()), array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $bSendMail = $this->sendEmail($buyer->getEmail(), $ssMailSubject, $asMailContent);
        }

        // Start code for send notification to BUYER's who offer on item which is to be SOLD.
        $c = new Criteria();
        $c->addDescendingOrderByColumn(CollectibleOfferPeer::UPDATED_AT);
        $c->add(CollectibleOfferPeer::COLLECTIBLE_ID, $collectible->getId());
        $c->add(CollectibleOfferPeer::COLLECTOR_ID, $buyer->getId(), Criteria::NOT_EQUAL);
        $c->add(CollectibleOfferPeer::STATUS, 'rejected', Criteria::NOT_EQUAL);
        $c->addAnd(CollectibleOfferPeer::STATUS, 'accepted', Criteria::NOT_EQUAL);
        $offers = CollectibleOfferPeer::doSelect($c);

        foreach ($offers as $o)
        {
          $o->setStatus('rejected');
          $o->save();

          $ssMailSubject = "You were outbid on an item at the CQ Marketplace";
          $ocqMail->setTemplate('marketplace_offer_rejected_buyer_outbid.html');

          $replacements['%buyer%'] = $o->getCollector()->getDisplayName();
          $replacements['%link%'] = link_to('Return to the site to look for other similar items', 'marketplace/index', array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $bSendMail = $this->sendEmail($o->getCollector()->getEmail(), $ssMailSubject, $asMailContent);

          /* OLD
            $subject = "You were outbid on an item at the CQ Marketplace";
            $mail->setTemplate('marketplace_offer_rejected_buyer_outbid.html');

            $replacements['%buyer%'] = $o->getCollector()->getDisplayName();
            $replacements['%link%'] = link_to('Return to the site to look for other similar items', 'marketplace/index', array('absolute' => true));
            $mail->send($o->getCollector()->getEmail(), $subject, $replacements);
           */
        }
        break;
      case 'reject':
        $offer->setStatus('rejected');
        $offer->save();

        if ($who == 'seller')
        {
          // New Mail Code Added By Prakash Panchal 8-APR-2011
          $ssMailSubject = "Your price was too low for an item at the CQ Marketplace";
          $ocqMail->setTemplate('marketplace_offer_rejected_buyer.html');

          $replacements['%link%'] = link_to('Email seller to get a better sense of a ballpark price', '@message_compose?to=' . $seller->getId() . '&subject=Regarding item ' . urlencode($collectible->getName()), array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $bSendMail = $this->sendEmail($buyer->getEmail(), $ssMailSubject, $asMailContent);
        }
        else
        {
          // New Mail Code Added By Prakash Panchal 8-APR-2011
          $ssMailSubject = "Your price was too high for an item at the CQ Marketplace";
          $ocqMail->setTemplate('marketplace_offer_rejected_seller.html');

          $replacements['%contact_link%'] = link_to('You can contact the buyer now if needed', '@message_compose?to=' . $buyer->getId() . '&subject=Regarding item ' . urlencode($collectible->getName()), array('absolute' => true));

          //replace mail content
          $asMailContent = $ocqMail->replaceMailContent($replacements);

          //send mail to user with swift mail functionality(Provided by symfony)
          $bSendMail = $this->sendEmail($buyer->getEmail(), $ssMailSubject, $asMailContent);
        }
        break;
    }

    $this->getUser()->setFlash('success', 'Status of the offer has been updated!');
    $this->redirect('marketplace_item_offers', $offer->getCollectibleForSale());
  }

  public function executeMakeOffer(sfWebRequest $request)
  {
    /* @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->getRoute()->getObject();
    $this->forward404Unless($collectible_for_sale);

    $this->forward404Unless($collectible_for_sale->getPrice() == $request->getParameter('offer'));

    $collectible = $collectible_for_sale->getCollectible();
    $seller = $collectible->getCollector();
    $buyer = $this->getUser()->getCollector();

    $this->forward404Unless($seller && $buyer && $collectible);

    $this->loadHelpers(array('cqLinks'));

    if ($request->isMethod('GET'))
    {
      $price = $request->getParameter('offer');
      if (floatval($price) == 0 && !$collectible_for_sale->getIsPriceNegotiable())
      {
        $price = $collectible_for_sale->getPrice();
      }

      $c = new Criteria();
      $c->add(CollectibleOfferPeer::COLLECTOR_ID, $buyer->getId());
      $c->add(CollectibleOfferPeer::STATUS, 'pending');
      $c->add(CollectibleOfferPeer::COLLECTIBLE_FOR_SALE_ID, $collectible_for_sale->getId());
      $offer = CollectibleOfferPeer::doSelectOne($c);

      if (!$offer)
      {
        $offer = new CollectibleOffer();
        $offer->setCollectorId($buyer->getId());
        $offer->setCollectibleId($collectible->getId());
        $offer->setCollectibleForSaleId($collectible_for_sale->getId());
        $offer->setPrice($price);
        $offer->setStatus('pending');
        $offer->save();

        // Send Mail To Seller
        $to = $seller->getEmail();
        $subject = "Congratulations! An agreement was made to purchase your item!";
        $body = $this->getPartial(
            'emails/seller_purchase_confirmation', array(
            'buyer' => $buyer, 'seller' => $seller,
            'collectible_for_sale' => $collectible_for_sale
            )
        );

        // Send off the email to the Seller
        $this->sendEmail($to, $subject, $body);

        // Send Mail To Buyer
        $to = $buyer->getEmail();
        $subject = "You have agreed to purchase an item in the CQ Marketplace!";
        $body = $this->getPartial(
            'emails/buyer_purchase_confirmation', array(
            'buyer' => $buyer, 'seller' => $seller,
            'collectible_for_sale' => $collectible_for_sale
            )
        );

        // Send off the email to the Buyer
        $this->sendEmail($to, $subject, $body);

        $this->getUser()->setFlash('success', 'The seller has been notified about your intent to buy this item!');

        return $this->redirect('@collectible_by_slug?id=' . $collectible->getId() . '&slug=' . $collectible->getSlug() . '&purchase=1');
      }
      else
      {
        $this->getUser()->setFlash('warning', 'You already made an offer for this item, please wait for action from the seller');
      }

      $this->redirect('@collectible_by_slug?id=' . $collectible->getId() . '&slug=' . $collectible->getSlug());
    }

    return sfView::NONE;
  }

  public function executeMakeCounterOffer()
  {
    $offer = $this->getRoute()->getObject();

    $collectible = $offer->getCollectible();

    $this->forward404Unless($this->getUser()->isOwnerOf($collectible) || $offer->getCollectorId() == $this->getUser()->getId());

    sfLoader::loadHelpers(array('Tag', 'Url', 'cqLinks'));
    if ($this->getUser()->isOwnerOf($collectible))
    {
      $who = 'seller';

      $seller = $this->getUser()->getCollector();
      $buyer = $offer->getCollector();
    }
    else
    {
      $who = 'buyer';

      $seller = $collectible->getCollection()->getCollector();
      $buyer = $this->getUser()->getCollector();
    }
    $replacements = array(
      '%seller%' => $seller->getDisplayName(),
      '%buyer%' => $buyer->getDisplayName(),
      '%collection_item%' => $collectible->getName(),
      '%price%' => money_format('%.2n', $this->getRequestParameter('offer')),
      '%image%' => link_to_collectible($collectible, 'image', array('width' => 100, 'height' => 100, 'style' => 'border: 0px;'))
    );

    if ($this->getRequest()->getMethod() == sfRequest::GET)
    {

      if ($who == 'seller')
      {
        $offer->setPrice($this->getRequestParameter('offer'));
        $offer->setStatus('counter');
        $offer->save();

        // New cqMail Added By Prakash Panchal. 7-APR-2011
        //get mail content according to name
        $ocqMail = new cqMail();
        $ocqMail->setTemplate('marketplace_counteroffer_buyer.html');
        $ssMailSubject = "Your offer was countered at the CQ Marketplace!";

        //set mail parameter for mail functionality
        $replacements['%link%'] = link_to('Click here to accept/reject or make a counter offer', 'marketplace_item_offers', $offer->getCollectibleForSale(), array('absolute' => true));

        //replace mail content
        $asMailContent = $ocqMail->replaceMailContent($replacements);

        //send mail to user with swift mail functionality(Provided by symfony)
        $this->sendEmail($buyer->getEmail(), $ssMailSubject, $asMailContent);
      }
      else
      {
        $offer->setPrice($this->getRequestParameter('offer'));
        $offer->setStatus('buyer_counter');
        $offer->save();

        // New cqMail Added By Prakash Panchal. 7-APR-2011
        // get mail content according to name
        $ocqMail = new cqMail();
        $ocqMail->setTemplate('marketplace_counteroffer_seller.html');
        $ssMailSubject = "Your offer was countered at the CQ Marketplace!";

        // set mail parameter for mail functionality
        $replacements['%link%'] = link_to('Click here to accept/reject or make a counter offer', 'marketplace_item_offers', $offer->getCollectibleForSale(), array('absolute' => true));

        // replace mail content
        $asMailContent = $ocqMail->replaceMailContent($replacements);

        // send mail to user with swift mail functionality(Provided by symfony)
        $this->sendEmail($seller->getEmail(), $ssMailSubject, $asMailContent);
      }

      return $this->redirect('marketplace_item_offers', $offer->getCollectibleForSale());
    }

    return sfView::NONE;
  }

  public function executeBuyNow(sfWebRequest $request)
  {
    /* @var $collectible CollectibleForSale */
    $collectibleForSale = $this->getRoute()->getObject();

    $this->forward404Unless($collectibleForSale);

    $request->setParameter('offer', $collectibleForSale->getPrice());

    $this->forward('marketplace', 'makeOffer');
  }

  public function executeSellItem(sfWebRequest $request)
  {
    if ($this->getUser()->getAttribute('id', '', 'collector'))
    {
      $this->bSeller = false;
      $this->omSeller = CollectorPeer::retrieveByPK($this->getUser()->getAttribute('id', '', 'collector'));
      if ($this->omSeller && $this->omSeller->getUserType() == 'Seller' && ($this->omSeller->getItemsAllowed() < 0 || $this->omSeller->getItemsAllowed() > 0))
      {
        $omPackageExpire = PackageTransactionPeer::checkExpiryDate($this->getUser()->getAttribute('id', '', 'collector'));
        if ($omPackageExpire && date("Y-m-d", strtotime($omPackageExpire->getExpiryDate())) >= date('Y-m-d'))
        {
          $this->bSeller = true;
          $snIdCollectibleForSale = $request->getParameter('id', 0);
          $snIdCollection = $request->getParameter('collection_id', 0);
          if ($snIdCollectibleForSale > 0 && $snIdCollection > 0)
          {
            $this->forward404Unless($omSaleItems = CollectibleForSalePeer::retrieveByPK($snIdCollectibleForSale), sprintf('Object collection item sale does not exist (%s).', $snIdCollectibleForSale));
            $this->forward404Unless($this->omCollection = CollectionPeer::retrieveByPK($snIdCollection), sprintf('Object collection does not exist (%s).', $snIdCollection));

            $this->oForm = new CollectibleForSaleForm($omSaleItems);
            $this->oCollectionForm = new CollectionForm($this->omCollection);
          }
          else
            $this->oForm = new CollectibleForSaleForm();

          $bIsEdit = ($request->getParameter('edit')) ? true : false;

          if ($request->getMethod() == sfRequest::POST)
          {
            $this->oForm->bind($request->getParameter($this->oForm->getName()), $request->getFiles($this->oForm->getName()));
            $bCollectible = true;
            if ($request->getParameter('item_id') == '')
            {
              $this->getUser()->setFlash('msg_collection_item', 'Please select collection item');
              $bCollectible = false;
            }
            // For Save Collection Details
            if ($bIsEdit)
            {
              $this->oCollectionForm->bind($request->getParameter($this->oCollectionForm->getName()), $request->getFiles($this->oCollectionForm->getName()));
              if ($this->oCollectionForm->isValid())
              {
                $this->oCollectionForm->save();
                $snPrice = $request->getParameter('collection_item_for_sale[price]');
                if (is_numeric($snPrice) || is_float($snPrice))
                  $this->getUser()->setFlash('success', 'Your changes were successfully saved.');
              }
            }
            // For Save Collection Item for Sale Details
            if ($this->oForm->isValid() && $bCollectible)
            {
              $this->oForm->save();
              // Update Collectible isForSale true
              $omCollectible = Collectible::updateItemIsForSale($request->getParameter('item_id'));
              if ($this->oForm->isNew())
              {
                // Less Allowed Items for sell.
                CollectorPeer::deductAllowedItems($this->getUser()->getCollector()->getId());

                $this->getUser()->setFlash('success', 'Your item is now listed for sale with the described terms.');
                $this->redirect('community/mymarket');
              }
            }
          }
          $this->addBreadcrumb('Sell Your Collectibles');
          $this->prependTitle('Sell Your Collectibles');

          return sfView::SUCCESS;
        }
        else
          $this->redirect('@seller_upgrade_package?id=' . $this->getUser()->getCollector()->getId() . '&msg=expired');
      }
      else
      {
        if ($this->omSeller->getUserType() != 'Seller')
          $this->redirect('@seller_become?id=' . $this->getUser()->getCollector()->getId());
        elseif ($this->omSeller->getItemsAllowed() == 0)
          $this->redirect('@seller_upgrade_package?id=' . $this->getUser()->getCollector()->getId());
      }
    }
    else
      $this->redirect('@login');
  }
}
