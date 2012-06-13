<?php

/**
 * Collectible filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class CollectibleFormFilter extends BaseCollectibleFormFilter
{

  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    if (isset($defaults['collection_collectible_list']) and is_array($defaults['collection_collectible_list']))
    {
      $defaults['collection_collectible_list'] = implode(',', $defaults['collection_collectible_list']);
    }

    if (isset($defaults['id']) and is_array($defaults['id']))
    {
      $defaults['id'] = implode(',', $defaults['id']);
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->widgetSchema['collection_collectible_list'] = new sfWidgetFormInputText(array('label' => 'Collection #'));
    $this->widgetSchema['id']                          = new sfWidgetFormInputText(array('label' => 'Collectible #'));

    $this->validatorSchema['collection_collectible_list']->setOption('multiple', true);
    $this->validatorSchema['id'] = new sfValidatorPropelChoice(array(
      'model'   => 'Collectible',
      'required'=> false,
      'multiple'=> true
    ));
  }

  protected function doBind(array $values)
  {
    if (isset($values['collection_collectible_list']))
    {
      $values['collection_collectible_list'] = explode(',', $values['collection_collectible_list']);
    }

    if (isset($values['id']))
    {
      $values['id'] = explode(',', $values['id']);
    }

    parent::doBind($values);
  }

  /**
   * @param \CollectibleQuery|\Criteria $criteria
   * @param string $field
   * @param array|null $values
   *
   * @return CollectibleQuery
   */
  public function addCollectionCollectibleListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return $criteria;
    }

    $criteria->joinCollectionCollectible();
    $criteria->useCollectionCollectibleQuery()
        ->filterByCollectionId($values, Criteria::IN)
        ->endUse();

    return $criteria;
  }

  /**
   * @param \CollectibleQuery|\Criteria $criteria
   * @param string $field
   * @param array|string|null $values
   *
   * @return CollectibleQuery
   */
  public function addIdColumnCriteria(Criteria $criteria, $field, $values = null)
  {
    if (null === $values)
    {
      return $criteria;
    }

    if (is_array($values))
    {
      $criteria->filterById($values, Criteria::IN);
    }
    else {
      $criteria->filterById($values, Criteria::EQUAL);
    }

    return $criteria;
  }

}
