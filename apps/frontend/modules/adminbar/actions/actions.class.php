<?php

/**
 * adminbar actions.
 *
 * @package    CollectorsQuest
 * @subpackage adminbar
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminbarActions extends sfActions
{

  public function executeRate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest());

    $dimension = $request->getParameter('dimension');
    $class = $request->getParameter('class');
    $id = $request->getParameter('id');

    $q = new CollectibleRateQuery();
    $q
      ->filterByCollectibleId((int) $id)
      ->filterByDimension($dimension)
      ->filterByCollectorId($this->getUser()->getCollector()->getId());
    $rate = $q->findOneOrCreate();

    $form = new CollectibleRateForm($rate);
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $form->save();
    }

    return $this->renderPartial('adminbar/rateForm', array('form' => $form, 'class' => $class, 'id' => $id));
  }
}
