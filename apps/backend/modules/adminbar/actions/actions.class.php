<?php

/**
 * adminbar actions.
 *
 * @package    CollectorsQuest
 * @subpackage adminbar
 * @author     Collectors Quest, Inc.
 */
class adminbarActions extends sfActions
{
  public function executeRating(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    $dimension = $request->getParameter('dimension');
    $class = $request->getParameter('class');

    // Define classes and methods names
    $classPeer =          sprintf('%sPeer', $class);
    $classRating =        sprintf('%sRating', $class);
    $classRatingPeer =    sprintf('%sPeer', $classRating);
    $classRatingQuery =   sprintf('%sQuery', $classRating);
    $countMethod =        sprintf('count%sRatings', $class);
    $getRatingsMethod =   sprintf('get%sRatings', $class);
    $filterMethod =       sprintf('filterBy%sId', $class);
    $setObjectIdMethod =  sprintf('set%sId', $class);

    // ObjectRatingForm should extend $class.RatingForm
    eval(sprintf('class ObjectRatingDynamicExtendForm extends %s {}', $class . 'RatingForm'));

    $id = (integer) $request->getParameter('id');
    $user_id = (integer) $request->getParameter('user_id');


    $object = $classPeer::retrieveByPK($id);

    $this->forward404Unless(method_exists($object, 'getAverageRating'));

    if ($request->isMethod(sfRequest::GET))
    {
      $this->class = get_class($object);
      $this->id = $object->getId();

      $c = new Criteria();
      $c->add($classRatingPeer::SF_GUARD_USER_ID, $user_id);

      $objRatings = $object->$getRatingsMethod($c);
      $temp = array();

      // Resort by Dimension
      foreach ($objRatings as $rating)
      {
        $temp[$rating->getDimension()] = $rating;
      }
      $objRatings = $temp;
      unset($temp);

      $forms = array();
      foreach ($classRatingPeer::getDimensions() as $key => $label)
      {
        if (isset($objRatings[$key]))
        {
          $rating = $objRatings[$key];
        }
        else
        {
          $rating = new $classRating();
          $rating
            ->setSfGuardUserId($user_id)
            ->$setObjectIdMethod($object->getId())
            ->setDimension($key);
        }
        $forms[$key] = new ObjectRatingForm($rating, array(), false);
      }

      $this->average_rating = $object->getAverageRating();
      $this->total_ratings = round($object->$countMethod() / count($classRatingPeer::getDimensions()));

      $this->forms = $forms;

      return sfView::SUCCESS;
    }
    else if ($request->isMethod(sfRequest::POST))
    {
      $result = array();

      $q = new $classRatingQuery();
      $q->$filterMethod($id)
        ->filterByDimension($dimension)
        ->filterBySfGuardUserId($user_id);
      $rating = $q->findOneOrCreate();

      /* @var $form sfFormPropel */
      $form = new ObjectRatingForm($rating, array(), false);
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $rating = $form->save();
      }
      else
      {
        // return form with error message
        $result['form'] = $this->getPartial(
          'adminbar/ratingForm', array('form' => $form, 'class' => $class, 'id' => $id)
        );
      }

      $result['dimension'] = $this->getPartial('adminbar/ratingTotal', array(
        'average_rating' => $rating->getAverageRating(),
        'total_ratings'  => $rating->getTotalRatings()
      ));

      $result['total'] = $this->getPartial('adminbar/ratingTotal', array(
        'average_rating' => $object->getAverageRating(),
        'total_ratings'  => round($object->$countMethod() / count($classRatingPeer::getDimensions()))
      ));

      return $this->renderText(json_encode($result));
    }
    else
    {
      return sfView::NONE;
    }
  }

  public function executeMachinetags(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    $this->class = $request->getParameter('class');
    $this->id = (integer) $request->getParameter('id');
    $this->user_id = (integer) $request->getParameter('user_id');

    // Define classes and methods names
    $classPeer = sprintf('%sPeer', $this->class);
    $classForm = sprintf('Base%sForm', $this->class);

    $this->forward404Unless($object = $classPeer::retrieveByPK($this->id));

    // MachineTagForm should extend $classForm
    eval(sprintf('class MachineTagDynamicExtendForm extends %s {}', $classForm));

    $form = new MachineTagForm($object, array(), false);

    if ($request->isMethod(sfRequest::POST))
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        $object = $form->save();
      }
    }

    $this->form = $form;
    return sfView::SUCCESS;
  }
}
