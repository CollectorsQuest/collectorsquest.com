<?php

require 'lib/model/om/BaseFeaturedNestedSet.php';

class Featured extends BaseFeaturedNestedSet
{
  private $_eblob = array();

  public function __get($name)
  {
    if (empty($this->_eblob))
    {
      $this->_eblob = unserialize($this->getEblob());
    }

    return isset($this->_eblob[$name]) ? $this->_eblob[$name] : null;
  }

  public function __set($name, $value)
  {
    if ($this->_eblob === null)
    {
      $this->_eblob = unserialize($this->getEblob());
    }

    $this->_eblob[$name] = $value;
  }

  public function save(PropelPDO $con = null)
  {
    $eblob = @unserialize($this->getEblob());
    $eblob = array_merge((array) $eblob, $this->_eblob);

    $this->setEblob(serialize($eblob));

    parent::save($con);
  }

  public function getCategoryIds()
  {
    $ids = array();

    foreach ($this->getChildren() as $child)
    {
      if ($child->getFeaturedModel() == 'CollectionCategory')
      {
        $ids[] = $child->getFeaturedId();
      }
    }

    return $ids;
  }

	public function getCollectorIds()
	{
    $ids = array();

    foreach ($this->getChildren() as $child)
    {
      if ($child->getFeaturedModel() == 'Collector')
      {
        $ids[] = $child->getFeaturedId();
      }
    }

    return $ids;
	}

  public function getCollectionIds()
  {
    $ids = array();

    foreach ($this->getChildren() as $child)
    {
      if ($child->getFeaturedModel() == 'Collection')
      {
        $ids[] = $child->getFeaturedId();
      }
    }

    return $ids;
  }

  public function getCollectibleIds()
  {
    $ids = array();

    foreach ($this->getChildren() as $child)
    {
      if ($child->getFeaturedModel() == 'Collectible')
      {
        $ids[] = $child->getFeaturedId();
      }
    }

    return $ids;
  }

  public function getCollections($limit = 5)
  {
    $pks = $collector_pks = $collection_category_pks = array();

    foreach ($this->getChildren() as $child)
    {
      switch($child->getFeaturedModel())
      {
        case 'CollectionCategory':
          $collection_category_pks[] = $child->getFeaturedId();

          foreach ($child->getChildren() as $c)
          {
            if ($c->getFeaturedModel() == 'Collector')
            {
              $collector_pks[] = $c->getFeaturedId();
            }
          }
          break;
        case 'Collection':
          $pks[] = $child->getFeaturedId();
          break;
        case 'Collector':
          $collector_pks[] = $child->getFeaturedId();
          break;
      }
    }

    $q = CollectionQuery::create();

    if (!empty($pks))                      $q->filterById($pks, Criteria::IN);
    if (!empty($collector_pks))            $q->filterByCollectorId($collector_pks, Criteria::IN);
    if (!empty($collection_category_pks))  $q->filterByCollectionCategoryId($collection_category_pks, Criteria::IN);

    $q->limit($limit);

    return $q->find();
  }

  public function getHomepageCollectible()
  {
    $q = new CollectibleQuery();

    $pks = explode(',', $this->homepage_collectibles);
    $pks = array_filter($pks);

    if (empty($pks))
    {
      $collector_pks = $collection_pks = $collection_category_pks = array();

      foreach ($this->getChildren() as $child)
      {
        switch($child->getFeaturedModel())
        {
          case 'CollectionCategory':
            $collection_category_pks[] = $child->getFeaturedId();

            foreach ($child->getChildren() as $c)
            {
              if ($c->getFeaturedModel() == 'Collector')
              {
                $collector_pks[] = $c->getFeaturedId();
              }
            }
            break;
          case 'Collection':
            $collection_pks[] = $child->getFeaturedId();
            break;
          case 'Collector':
            $collector_pks[] = $child->getFeaturedId();
            break;
        }
      }

      if (!empty($collection_category_pks))
      {
        $q->join('Collectible.Collection');
        $q->useQuery('Collection')
          ->filterByCollectionCategoryId($collection_category_pks)
          ->endUse();
      }
      if (!empty($collector_pks))
      {
        $q->filterByCollectorId($collector_pks);
      }
      if (!empty($collection_pks))
      {
        $q->filterByCollectionId($collection_pks);
      }
    }
    else
    {
      $q->filterByPrimaryKeys($pks);
    }

		  return ($q->hasWhereClause()) ? $q->findOne() : null;
  }
}
