<?php

require 'lib/model/om/BaseContentCategory.php';

/**
 *
 * @method     ContentCategory setSeoCollectionsTitlePrefix(string $v)
 * @method     string    getSeoCollectionsTitlePrefix()
 *
 * @method     ContentCategory setSeoMarketTitlePrefix(string $v)
 * @method     string    getSeoMarketTitlePrefix()
 *
 * @method     ContentCategory setSeoCollectionsTitleSuffix(string $v)
 * @method     string    getSeoCollectionsTitleSuffix()
 *
 * @method     ContentCategory setSeoMarketTitleSuffix(string $v)
 * @method     string    getSeoMarketTitleSuffix()
 *
 * @method     ContentCategory setSeoCollectionsDescription(string $v)
 *
 * @method     ContentCategory setSeoCollectionsKeywords(string $v)
 * @method     string    getSeoCollectionsKeywords()
 *
 * @method     ContentCategory setSeoMarketDescription(string $v)
 *
 * @method     ContentCategory setSeoMarketKeywords(string $v)
 * @method     string    getSeoMarketKeywords()
 *
 * @method     ContentCategory setSeoCollectionsUseSingular(boolean $v)
 * @method     boolean   getSeoCollectionsUseSingular()
 *
 * @method     ContentCategory setSeoMarketUseSingular(boolean $v)
 * @method     boolean   getSeoMarketUseSingular()
 */

class ContentCategory extends BaseContentCategory
{

  protected $autoSort = true;
  protected $isAutoSorting = false;
  protected $path = array();

  /**
   * Register extra properties to allow magic getters/setters to be used
   *
   * @see     ExtraPropertiesBehavior
   */
  public function initializeProperties()
  {
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_TITLE_PREFIX);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_TITLE_SUFFIX);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_DESCRIPTION);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_KEYWORDS);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_USE_SINGULAR);

    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_TITLE_PREFIX);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_TITLE_SUFFIX);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_DESCRIPTION);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_KEYWORDS);
    $this->registerProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_USE_SINGULAR);
  }

  /**
   * Retrieve a text representation of the path to this content category, ex:
   * Art / Asian / Vases
   *
   * @param     string $glue
   * @param     string $column, the column to be used to generate the path
   *
   * @return    string
   */
  public function getPath($glue = ' / ', $column = 'Name')
  {
    if (!isset($this->path[$column]))
    {
      $ancestors = ContentCategoryQuery::create()
        ->ancestorsOfObjectIncluded($this)
        ->notRoot()
        ->orderByTreeLevel()
        ->select($column)
        ->find()->getArrayCopy();

      $this->path[$column] = $ancestors;
    }

    return implode($glue, $this->path[$column]);
  }

  /**
   * Slug path used for url generation
   *
   * @return    string
   */
  public function getSlugPath()
  {
    return $this->getPath('/', 'Slug');
  }

  /**
   * Enable/Disable auto sorting siblings for this object
   *
   * @param     boolean $value
   * @return    ContentCategory
   */
  public function setAutoSort($value)
  {
    $this->autoSort = !! $value;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getAutoSort()
  {
    return $this->autoSort;
  }

  /**
   * Add sibling sorting in post-save
   *
   * When the Content Category is saved it and all its siblings are oredered
   * alphabetically in the Nested Set tree
   *
   * @param     PropelPDO $con
   * @return    boolean
   */
  public function postSave(PropelPDO $con = null)
  {
    if ($this->isAutoSorting)
    {
      $this->isAutoSorting = false;
      return true;
    }

    if ($this->isRoot() || !$this->getAutoSort())
    {
      return true;
    }
    // force the parent to be loaded from the DB
    $this->setParent(null);
    $siblings = ContentCategoryQuery::create()
          ->childrenOf($this->getParent($con))
          ->orderByBranch()
          ->setFormatter(ModelCriteria::FORMAT_ARRAY)
          ->find($con);

    if (!$siblings instanceof PropelArrayCollection || $siblings->count() == 1)
    {
      return true;
    }

    $names = $sorted_names = $siblings->toKeyValue('Id', 'Name');

    // use native case sort for sorted names
    natcasesort($sorted_names);

    // handle force names sorted to top or bottom
    foreach (ContentCategoryPeer::$force_order_to_top as $name_to_top)
    {
      if (false !== $key = array_search($name_to_top, $sorted_names))
      {
        unset ($sorted_names[$key]);
        // we want to add the element in the first position, but with a specific key
        // so we use the specail properties of the addition operator for arrays
        $sorted_names = array($key => $name_to_top) + $sorted_names;
      }
    }
    foreach (ContentCategoryPeer::$force_order_to_bottom as $name_to_bottom)
    {
      if (false !== $key = array_search($name_to_bottom, $sorted_names))
      {
        unset ($sorted_names[$key]);
        // we want to add the element in the last position, but with a specific key
        // so we use the specail properties of the addition operator for arrays
        $sorted_names = $sorted_names + array($key => $name_to_bottom);
      }
    }

    if ($sorted_names === $names)
    {
      // siblings already sorted, nothing to do here.
      return true;
    }

    // create an iterator for the sorted names
    $iter = new ArrayIterator($sorted_names);

    // move internal array pointer to first element
    $iter->rewind();
    // setup a prev variable (ArrayIterator cannot seek backwards)
    $prev = false;
    // find this object's position
    while ($iter->key() != $this->getPrimaryKey())
    {
      $prev = $iter->key();
      $iter->next();
    }

    // if there is a next element
    $iter->next();
    if ($iter->valid())
    {
      $sibling = ContentCategoryPeer::retrieveByPK($iter->key());
      $sibling->setAutoSort(false);
      $this->moveToPrevSiblingOf($sibling);
      $this->isAutoSorting = true;
      $this->save();
    }
    // if there was a previous element
    else if (false !== $prev)
    {
      $sibling = ContentCategoryPeer::retrieveByPK($prev);
      $sibling->setAutoSort(false);
      $this->setAutoSort(false);
      // move this object after it
      $this->moveToNextSiblingOf($sibling);
      $this->isAutoSorting = true;
      $this->save();
    }

    return true;
  }

  /**
   * A special case of getParent() where we want to get the Ancestor
   * at a certain level rather than the immediate parent
   *
   * @param  integer    $level
   * @param  PropelPDO  $con
   *
   * @return null|ContentCategory
   */
  public function getAncestorAtLevel($level, PropelPDO $con = null)
  {
    if ($level < 0)
    {
      $level = $this->getTreeLevel() - abs($level);
    }

    if (!$this->hasParent() || $level < 0)
    {
      $parent = null;
    }
    else
    {
      $parent = ContentCategoryQuery::create()
        ->ancestorsOf($this)
        ->filterByLevel($level, Criteria::EQUAL)
        ->findOne($con);
    }

    return $parent;
  }

  /**
   * @return array|PropelObjectCollection|ContentCategory[]
   */
  public function getChildrenWithCollections()
  {
    $q = ContentCategoryQuery::create()
      ->hasCollections()
      ->orderBy('Name');

    return $this->getChildren($q);
  }

  /**
   * Page SEO
   */
  public function getSeoCollectionsTitle()
  {
    $prefix = $this->getSeoCollectionsTitlePrefix() ?: 'Collectible';
    $suffix = $this->getSeoCollectionsTitleSuffix() ? ' '. $this->getSeoCollectionsTitleSuffix() : null;
    $name = $this->getSeoCollectionsUseSingular() ? $this->getNameSingular() : $this->getName();

    $title = $prefix .' '. $name . $suffix .' on Collectors Quest';

    if (!$keywords = $this->getSeoCollectionsKeywords())
    {
      $q = ContentCategoryQuery::create()
        ->hasCollectionsWithCollectibles()
        ->limit(3);

      /** @var $descendants ContentCategory[] */
      $descendants = $this->getDescendants($q);

      $names = array();
      foreach ($descendants as $descendant)
      {
        $names[] = $descendant->getName();
      }

      if (!empty($names))
      {
        $title .= ' - ' . implode(', ', $names);
      }
    }
    else
    {
      $title .= ' - ' . $keywords;
    }

    return $title;
  }

  public function getSeoCollectionsDescription()
  {
    if (!$description = $this->getProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_DESCRIPTION))
    {
      $description = strip_tags($this->getDescription());
    }

    return cqStatic::truncateText($description, 160, '', true);
  }

  public function getSeoMarketTitle()
  {
    $prefix = $this->getSeoMarketTitlePrefix() ?: 'Buy';
    $suffix = $this->getSeoMarketTitleSuffix() ? ' '. $this->getSeoMarketTitleSuffix() : null;
    $name = $this->getSeoMarketUseSingular() ? $this->getNameSingular() : $this->getName();

    $title = $prefix .' '. $name . $suffix .' on Collectors Quest';

    if (!$keywords = $this->getSeoCollectionsKeywords())
    {
      // @todo: $q->hasCollectionsWithCollectiblesForSale()
      $q = ContentCategoryQuery::create()
        ->hasCollectionsWithCollectibles()
        ->limit(3);

      /** @var $descendants ContentCategory[] */
      $descendants = $this->getDescendants($q);

      $names = array();
      foreach ($descendants as $descendant)
      {
        $names[] = $descendant->getName();
      }

      if (!empty($names))
      {
        $title .= ' - ' . implode(', ', $names);
      }
    }
    else
    {
      $title .= ' - ' . $keywords;
    }

    return $title;
  }

  public function getSeoMarketDescription()
  {
    if (!$description = $this->getProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_DESCRIPTION))
    {
      $description = strip_tags($this->getDescription());
    }

    return cqStatic::truncateText($description, 160, '', true);
  }

  /**
   * Compute the number of relater collectibles for sale
   *
   * @param     PropelPDO $con
   * @return    integer
   */
  public function computeNumCollectiblesForSale(PropelPDO $con)
  {
    return CollectibleQuery::create()
      ->isPartOfCollection()
      ->isForSale()
      ->filterByIsPublic(true)
      ->filterByContentCategoryWithDescendants($this)
      ->count($con);
  }

}
