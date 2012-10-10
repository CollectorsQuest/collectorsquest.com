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

    //define classes and methods names
    $classPeer = $class.'Peer';
    $classRate = $class.'Rate';
    $classRatePeer = $classRate.'Peer';
    $classRateQuery = $classRate.'Query';
    $countMethod = 'count' . $class . 'Rates';
    $getRatesMethod = 'get' . $class . 'Rates';
    $filterMethod = 'filterBy' . $class . 'Id';
    $setObjectIdMethod = 'set' . $class . 'Id';

    //ObjectRateForm should extend $class.RateForm
    eval(sprintf('class ObjectRateDynamicExtendForm extends %s {}', $class . 'RateForm'));

    $id = (integer) $request->getParameter('id');
    $user_id = (integer) $request->getParameter('bc');


    $object = $classPeer::retrieveByPK($id);

    if ($request->isMethod(sfRequest::GET))
    {
      $this->class = get_class($object);
      $this->id = $object->getId();

      $c = new Criteria();
      $c->add($classRatePeer::SF_GUARD_USER_ID, $user_id);

      $objRates = $object->$getRatesMethod($c);
      $temp = array();
      //resort by Dimension
      foreach ($objRates as $rate)
      {
        $temp[$rate->getDimension()] = $rate;
      }
      $objRates = $temp;
      unset($temp);

      $forms = array();
      foreach ($classRatePeer::getDimensions() as $key => $label)
      {
        if (isset($objRates[$key]))
        {
          $rate = $objRates[$key];
        }
        else
        {
          $rate = new $classRate();
          $rate
            ->setSfGuardUserId($user_id)
            ->$setObjectIdMethod($object->getId())
            ->setDimension($key);
        }
        $forms[$key] = new ObjectRateForm($rate, array(), false);
      }

      $this->average_rate = $object->getAverageRate();
      $this->total_rates = round($object->$countMethod() / count($classRatePeer::getDimensions()));

      $this->forms = $forms;

      return sfView::SUCCESS;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $result = array();
      $q = new $classRateQuery();
      $q
        ->$filterMethod($id)
        ->filterByDimension($dimension)
        ->filterBySfGuardUserId($user_id);
      $rate = $q->findOneOrCreate();

      $form = new ObjectRateForm($rate, array(), false);
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
        'total_rates' => round($object->$countMethod() / count($classRatePeer::getDimensions()))
      ));
      return $this->renderText(json_encode($result));
    }

  }
}
