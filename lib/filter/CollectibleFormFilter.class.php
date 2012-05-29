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

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->widgetSchema['collection_collectible_list'] = new sfWidgetFormInputText(array('label'=> 'Collection #'));

    $this->validatorSchema['collection_collectible_list']->setOption('multiple', true);
  }

  protected function doBind(array $values)
  {
    if (isset($values['collection_collectible_list']))
    {
      $values['collection_collectible_list'] = explode(',', $values['collection_collectible_list']);
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

}
