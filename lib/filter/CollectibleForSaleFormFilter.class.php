<?php

/**
 * CollectibleForSale filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 */
class CollectibleForSaleFormFilter extends BaseCollectibleForSaleFormFilter
{
  public function configure()
  {
    $this->useFields(array('price', 'condition', 'is_price_negotiable', 'is_shipping_free', 'is_sold', 'created_at'));

    $this->setWidget('price', new cqWidgetFormRange(array(
        'from' => new sfWidgetFormInput(),
        'to' => new sfWidgetFormInput(),
      )));

    $this->setValidator('price', new cqValidatorNumberRange(array(
        'from' => new sfValidatorNumber(array('required' => false)),
        'to' => new sfValidatorNumber(array('required' => false)),
      )));

    $this->setWidget('condition', new sfWidgetFormChoice(array('choices' => CollectibleForSalePeer::$conditions)));
    $this->setValidator('condition', new sfValidatorChoice(array('choices' => array_keys(CollectibleForSalePeer::$conditions), 'required' => false)));

    $this->setWidget('seller', new sfWidgetFormPropelJQueryAutocompleter(array(
        'model' => 'Collector',
        'url' => sfContext::getInstance()->getController()->genUrl('collectors/list')
      )));
    $this->setValidator('seller', new sfValidatorPropelChoice(array(
        'model' => 'Collector',
        'required' => false,
      )));
    
    $this->setWidget('offers_count', new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes'/*, 0 => 'no'*/))));
    $this->setValidator('offers_count', new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))));
  }

  public function buildCriteria(array $values)
  {
    if (isset($values['is_sold']))
    {
      $isSold = (bool) $values['is_sold'];
      unset($values['is_sold']);
    }

    if (!empty($values['price']['from']) or !empty($values['price']['to'])) {
      $values['price'] = array('min' => @$values['price']['from'], 'max' => @$values['price']['to']);
    } else {
      unset($values['price']);
    }
    $criteria = parent::buildCriteria($values);

//    CollectibleForSalePeer::addSelectColumns($criteria);

    if (isset($isSold))
    {
      $criteria->addJoin(CollectibleForSalePeer::ID, CollectibleOfferPeer::COLLECTIBLE_FOR_SALE_ID, Criteria::LEFT_JOIN);

      if ($isSold)
      {
        $criteria->add(CollectibleOfferPeer::STATUS, 'accepted', Criteria::EQUAL);
      }
      else
      {
        $crit = $criteria->getNewCriterion(CollectibleOfferPeer::STATUS, null, Criteria::ISNULL);
        $crit->addOr($criteria->getNewCriterion(CollectibleOfferPeer::STATUS, 'accepted', Criteria::NOT_EQUAL));

        $criteria->add($crit);
      }
    }

    return $criteria;
  }

  public function getFields()
  {
    $fields = parent::getFields();
    
    $fields['price'] = 'Price';
    
    return $fields;
  }

}
