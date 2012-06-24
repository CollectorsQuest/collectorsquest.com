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
    $this->setWidget('price', new cqWidgetFormRange(array(
      'from' => new sfWidgetFormInput(),
      'to'   => new sfWidgetFormInput(),
    )));

    $this->setValidator('price', new cqValidatorNumberRange(array(
      'from' => new sfValidatorNumber(array('required' => false)),
      'to'   => new sfValidatorNumber(array('required' => false)),
    )));

    $this->setWidget('condition', new sfWidgetFormChoice(array(
      'choices' => CollectibleForSalePeer::$conditions
    )));
    $this->setValidator('condition', new sfValidatorChoice(array(
      'choices' => array_keys(CollectibleForSalePeer::$conditions), 'required' => false
    )));

    $this->setWidget('seller', new sfWidgetFormPropelJQueryAutocompleter(array(
      'model' => 'Collector',
      'url'   => sfContext::getInstance()->getController()->genUrl('collectors/list')
    )));
    $this->setValidator('seller', new sfValidatorPropelChoice(array(
      'model'    => 'Collector',
      'required' => false,
    )));

  }

  public function buildCriteria(array $values)
  {
    if (!empty($values['price']['from']) or !empty($values['price']['to']))
    {
      $values['price'] = array(
        'min' => @$values['price']['from'], 'max' => @$values['price']['to']
      );
    }
    else
    {
      unset($values['price']);
    }
    $criteria = parent::buildCriteria($values);

    return $criteria;
  }

  public function getFields()
  {
    $fields = parent::getFields();
    $fields['price'] = 'Price';

    return $fields;
  }

}
