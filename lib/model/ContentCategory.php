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

    $names = $siblings->toKeyValue('Id', 'Name');

    $iter = new ArrayIterator($names);
    $iter->natcasesort();

    if ($names === $iter->getArrayCopy())
    {
      // siblings already sorted, nothing to do here.
      return true;
    }

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
