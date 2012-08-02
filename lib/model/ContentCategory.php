<?php

require 'lib/model/om/BaseContentCategory.php';

class ContentCategory extends BaseContentCategory
{

  protected $autoSort = true;
  protected $isAutoSorting = false;

  /**
   * Retrieve a text representation of the path to this content category, ex:
   * Art / Asian / Vases
   *
   * @param     string $glue
   * @return    string
   */
  public function getPath($glue = ' / ')
  {
    $ancestors = ContentCategoryQuery::create()
      ->ancestorsOfObjectIncluded($this)
      ->notRoot()
      ->orderByTreeLevel()
      ->select('Name')
      ->find()->getArrayCopy();

    return implode($glue, $ancestors);
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

}
