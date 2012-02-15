<?php

/**
 * CollectibleForSale form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 */
class CollectibleForSaleForm extends BaseCollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->useFields(array(
      'price',
      'condition',
      'is_shipping_free',
      'is_ready',
      'quantity',
    ));

    $amConditions = CollectibleForSalePeer::$conditions;
    unset($amConditions['']);

    //set Widgets
    $this->setWidget('condition', new sfWidgetFormSelect(array('choices' => $amConditions), array("class" => "text", "style" => "font-size: 14pt; margin-bottom: 5px;")));

    $omUserCollections = CollectorCollectionPeer::getCollectionAsPerCollector(sfContext::getInstance()->getUser()->getAttribute('id', '', 'collector'));
    $this->amUserCollections = array('' => '---- Select Collection ----');

    if ($omUserCollections)
    foreach ($omUserCollections as $amDataset)
    {
      $this->amUserCollections[$amDataset['ID']] = $amDataset['NAME'];
    }

    // set Validation
    $this->setValidator('price', new sfValidatorNumber(array('required' => true), array('required' => 'Please enter price', 'invalid' => 'Please enter valid price')));
    $this->setValidator('condition', new sfValidatorChoice(array('choices' => array_keys($amConditions), 'required' => false)));

    $this->setDefault('is_price_negotiable', false);
    $this->setDefault('is_shipping_free', false);

    //set Labels
    $this->widgetSchema->setLabel(array(
      'price' => 'Price',
      'condition' => 'Condition',
    ));


    $this->widgetSchema->setNameFormat('collection_item_for_sale[%s]');

//    $this->validatorSchema->setOption('allow_extra_fields', true);
//    $this->validatorSchema->setOption('filter_extra_fields', false);
    // Disable the secret key
    $this->disableLocalCSRFProtection();
  }

//  public function updateObject($values = null)
//  {
//    $values = $this->getValues();
//
//    $this->values['is_price_negotiable'] = isset($values['is_price_negotiable']) ? 1 : 0;
//    $this->values['is_shipping_free'] = isset($values['is_shipping_free']) ? 1 : 0;
//
//    parent::updateObject($this->values);
//  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->validatorSchema['price']->setOption('required', !empty($taintedValues['is_ready']));

    parent::bind($taintedValues, $taintedFiles);
  }

  public function save($con = null)
  {
    /** @var $object Collectible */
    $object = parent::save($con);

    if ($this->getValue('is_ready'))
    {
      $collector = $object->getCollector();
      $collector->setItemsAllowed($collector->getItemsAllowed() - 1);
      $collector->save($con);
    }

    return $object;
  }
}
