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
    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    $dimension = $request->getParameter('dimension');
    $class = $request->getParameter('class');
    $id = (integer) $request->getParameter('id');
    $user_id = 12;

    /* @var $object Collectible */
    $object = CollectiblePeer::retrieveByPK($id);

    if ($request->isMethod(sfRequest::GET))
    {
      $this->class = get_class($object);
      $this->id = $object->getId();

      $c = new Criteria();
      $c->add(CollectibleRatePeer::COLLECTOR_ID, $user_id);
      /** @var $objRates CollectibleRate[] */
      $objRates = $object->getCollectibleRates($c);
      $temp = array();
      //resort by Dimension
      foreach ($objRates as $rate)
      {
        $temp[$rate->getDimension()] = $rate;
      }
      $objRates = $temp;
      unset($temp);

      $forms = array();
      foreach (CollectibleRatePeer::getDimensions() as $key => $label)
      {
        if (isset($objRates[$key]))
        {
          $rate = $objRates[$key];
        }
        else
        {
          $rate = new CollectibleRate();
          $rate
            ->setCollectorId($user_id)
            ->setCollectibleId($object->getId())
            ->setDimension($key);
        }
        $forms[$key] = new CollectibleRateForm($rate, array(), false);
      }

      $this->average_rate = $object->getAverageRate();
      $this->total_rates = round($object->countCollectibleRates() / count(CollectibleRatePeer::getDimensions()));

      $this->forms = $forms;

      return sfView::SUCCESS;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $result = array();
      $q = new CollectibleRateQuery();
      $q
        ->filterByCollectibleId($id)
        ->filterByDimension($dimension)
        ->filterByCollectorId($user_id);
      $rate = $q->findOneOrCreate();

      $form = new CollectibleRateForm($rate, array(), false);
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
       $rate = $form->save();
      }
      else
      {
        // return form with error message
        $result['form'] = $this->getPartial(
          'adminbar/rateForm', array('form' => $form, 'class' => $class, 'id' => $id)
        );
      }
      $result['dimension'] = $this->getPartial('adminbar/rateTotal', array(
        'average_rate' =>$rate->getAverageRate(),
        'total_rates' =>$rate->getTotalRates()
      ));
      $result['total'] = $this->getPartial('adminbar/rateTotal', array(
        'average_rate' =>$object->getAverageRate(),
        'total_rates' => round($object->countCollectibleRates() / count(CollectibleRatePeer::getDimensions()))
      ));
      return $this->renderText(json_encode($result));
    }

  }
}
