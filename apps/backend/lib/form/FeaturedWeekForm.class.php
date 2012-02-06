<?php

/**
 * Featured form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 */
class FeaturedWeekForm extends BaseFeaturedForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'title'          => new sfWidgetFormInputText(),
      'homepage_text'  => new sfWidgetFormTextarea(),
      'start_date'     => new sfWidgetFormJQueryDate(),
      'end_date'       => new sfWidgetFormJQueryDate(),
      'is_active'      => new sfWidgetFormInputCheckbox(),

      'category_ids'     => new sfWidgetFormInputText(),
      'collector_ids'    => new sfWidgetFormInputText(),
      'collection_ids'   => new sfWidgetFormInputText(),
      'collectible_ids'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'title'          => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'homepage_text'  => new sfValidatorString(array('max_length' => 140, 'required' => true)),
      'start_date'     => new sfValidatorDate(array('required' => true)),
      'end_date'       => new sfValidatorDate(array('required' => false)),
      'is_active'      => new sfValidatorBoolean(),

      'category_ids'     => new sfValidatorString(array('required' => false)),
      'collector_ids'    => new sfValidatorString(array('required' => false)),
      'collection_ids'   => new sfValidatorString(array('required' => false)),
      'collectible_ids'  => new sfValidatorString(array('required' => false))
    ));

    if ($featured = $this->getObject())
    {
      $this->setDefault('category_ids', implode(', ', $featured->getCategoryIds()));
      $this->setDefault('collector_ids', implode(', ', $featured->getCollectorIds()));
      $this->setDefault('collection_ids', implode(', ', $featured->getCollectionIds()));
      $this->setDefault('collectible_ids', implode(', ', $featured->getCollectibleIds()));
    }

    $this->widgetSchema->setNameFormat('featured_week[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

  public function updateObject($values = null)
  {
    /** @var $object Featured */
    $object = parent::updateObject($values);

    if (null === $values)
    {
      $values = $this->values;
    }
    $object->title = $values['title'];
    $object->homepage_text = $values['homepage_text'];

    if ($object->isNew())
    {
      $object->setFeaturedTypeId(6);
      $object->setFeaturedModel('FeaturedWeek');
      $object->makeRoot();
      $object->save();
    }

    $category_ids    = array_map('trim', explode(',', $values['category_ids']));
    $collector_ids   = array_map('trim', explode(',', $values['collector_ids']));
    $collection_ids  = array_map('trim', explode(',', $values['collection_ids']));
    $collectible_ids = array_map('trim', explode(',', $values['collectible_ids']));

    /** @var Featured $child */
    foreach ($object->getChildren() as $child)
    {
      switch ($child->getFeaturedModel())
      {
        case 'CollectionCategory':
          if (!in_array($child->getFeaturedId(), $category_ids)) {
            $child->delete();
          } else {
            $category_ids = array_diff($category_ids, array($child->getFeaturedId()));
          }
          break;
        case 'Collector':
          if (!in_array($child->getFeaturedId(), $collector_ids)) {
            $child->delete();
          } else {
            $collector_ids = array_diff($collector_ids, array($child->getFeaturedId()));
          }
          break;
        case 'Collection':
          if (!in_array($child->getFeaturedId(), $collection_ids)) {
            $child->delete();
          } else {
            $collection_ids = array_diff($collection_ids, array($child->getFeaturedId()));
          }
          break;
        case 'Collectible':
          if (!in_array($child->getFeaturedId(), $collectible_ids)) {
            $child->delete();
          } else {
            $collectible_ids = array_diff($collectible_ids, array($child->getFeaturedId()));
          }
          break;
      }
    }

    if (($category_ids = array_filter($category_ids)) && !empty($category_ids))
    {
      foreach ($category_ids as $category_id)
      {
        $featured = new Featured();
        $featured->setFeaturedTypeId(6);
        $featured->setFeaturedModel('CollectionCategory');
        $featured->setFeaturedId($category_id);
        $featured->setIsActive(true);
        $featured->insertAsLastChildOf($object);
        $featured->save();
      }
    }
    else if (empty($values['category_ids']))
    {
      FeaturedQuery::create()->filterByTreeScope($object->getId())->filterByFeaturedModel('CollectionCategory')->delete();
    }

    if (($collector_ids = array_filter($collector_ids)) && !empty($collector_ids))
    {
      foreach ($collector_ids as $collector_id)
      {
        $featured = new Featured();
        $featured->setFeaturedTypeId(6);
        $featured->setFeaturedModel('Collector');
        $featured->setFeaturedId($collector_id);
        $featured->setIsActive(true);
        $featured->insertAsLastChildOf($object);
        $featured->save();
      }
    }
    else if (empty($values['collector_ids']))
    {
      FeaturedQuery::create()->filterByTreeScope($object->getId())->filterByFeaturedModel('Collector')->delete();
    }

    if (($collection_ids = array_filter($collection_ids)) && !empty($collection_ids))
    {
      foreach ($collection_ids as $collection_id)
      {
        $featured = new Featured();
        $featured->setFeaturedTypeId(6);
        $featured->setFeaturedModel('Collection');
        $featured->setFeaturedId($collection_id);
        $featured->setIsActive(true);
        $featured->insertAsLastChildOf($object);
        $featured->save();
      }
    }
    else if (empty($values['collection_ids']))
    {
      FeaturedQuery::create()->filterByTreeScope($object->getId())->filterByFeaturedModel('Collection')->delete();
    }

    if (($collectible_ids = array_filter($collectible_ids)) && !empty($collectible_ids))
    {
      foreach ($collectible_ids as $collectible_id)
      {
        $featured = new Featured();
        $featured->setFeaturedTypeId(6);
        $featured->setFeaturedModel('Collectible');
        $featured->setFeaturedId($collectible_id);
        $featured->setIsActive(true);
        $featured->insertAsLastChildOf($object);
        $featured->save();
      }
    }
    else if (empty($values['collectible_ids']))
    {
      FeaturedQuery::create()->filterByTreeScope($object->getId())->filterByFeaturedModel('Collectible')->delete();
    }

    return $object;
  }
}
