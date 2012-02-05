<?php

class CollectionCategory extends BaseCollectionCategory
{
  public function __toString()
  {
    return $this->getName();
  }

  public function getNameWithParent()
  {
    $name = $this->getName();

    if ($this->getParentId())
    {
      $name .= ' ('. $this->getParent()->getName() .')';
    }

    return $name;
  }

  public function getParent()
  {
    return CollectionCategoryQuery::create()->findOneById($this->getParentId());
  }

  public function getCountFields()
  {
    $c = new Criteria();
    $c->add(CollectionCategoryFieldPeer::COLLECTION_CATEGORY_ID, $this->getId());

    return CollectionCategoryFieldPeer::doCountJoinCollectionCategory($c);
  }

  public function getCollectionsCount()
  {
    return CollectionQuery::create()->filterByCollectionCategoryId($this->getId())->count();
  }

  public function addField($field)
  {
    if (is_numeric($field))
    {
      $field = CustomFieldPeer::retrieveByPK($field);
    }
    if (!($field instanceof CustomField))
    {
      return false;
    }

    $collection_category_field = new CollectionCategoryField();
    $collection_category_field->setCollectionCategory($this);
    $collection_category_field->setCustomField($field);

    return $collection_category_field->save();
  }

  public function getCustomFields($output = 'array')
  {
    $c = new Criteria();
    $c->addSelectColumn(CustomFieldPeer::ID);
    $c->addSelectColumn(CustomFieldPeer::NAME);
    $c->addSelectColumn(CustomFieldPeer::OBJECT);
    $c->addJoin(CustomFieldPeer::ID, CollectionCategoryFieldPeer::CUSTOM_FIELD_ID);
    $c->add(CollectionCategoryFieldPeer::COLLECTION_CATEGORY_ID, $this->getId());

    if ($output == 'array')
    {
      $fields = array();
      $stmt = CustomFieldPeer::doSelectStmt($c);
      while ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $fields[$row[0]] = array('name' => $row[1], 'object' => unserialize($row[2]));
      }
    }
    else
    {
      $fields = CustomFieldPeer::doSelect($c);
    }

    return $fields;
  }

  public function getTagsString()
  {
    return implode(', ', $this->getTags());
  }

  public function getTagIds()
  {
    $tag_ids = array();

    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'CollectionCategory');

    $stmt = iceModelTaggingPeer::doSelectStmt($c);
    while ($tag_id = $stmt->fetchColumn(0))
    {
      $tag_ids[] = (int) $tag_id;
    }

    return $tag_ids;
  }

  public function getSlug()
  {
    return Utf8::slugify($this->getName());
  }
}

sfPropelBehavior::add('CollectionCategory', array('IceTaggableBehavior'));
