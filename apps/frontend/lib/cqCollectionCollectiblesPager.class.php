<?php
/**
 * Pager forCollection Collectibles Widget
 */

class cqCollectionCollectiblesPager extends sfPager
{
  /* @var Collection */
  protected $collection = null;

  /* @var null|PropelCollection */
  protected $results = null;

  protected $collectible_id = null;

  public function __construct(Collection $collection, $maxPerPage = 3)
  {
    parent::__construct(null, $maxPerPage);

    $this->collection = $collection;
  }

  /**
   * Initialize the pager.
   *
   * Function to be called after parameters have been set.
   *
   * @return MagnifyFeed
   */
  public function init()
  {
    $selectByOne = false;

    /* @var $stmt PDOStatemen */
    $stmt = FrontendCollectionCollectibleQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_STATEMENT)
      ->filterByCollection($this->collection)
      ->orderByPosition()
      ->addSelectColumn(CollectionCollectiblePeer::COLLECTIBLE_ID)
      ->find();
    $collectible_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $this->setNbResults(count($collectible_ids));

    if (($this->getPage() == 0 || $this->getMaxPerPage() == 0))
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage((int) ceil($this->getNbResults() / $this->getMaxPerPage()));
    }

    if (!$this->haveToPaginate())
    {
      $ids = $collectible_ids;
    }
    else
    {
      $ids = array();

      $pointer = (
        (
          $this->getPage() <= $this->getLastPage() ? $this->getPage() : $this->getLastPage()
        )
          - 1) * $this->getMaxPerPage();

      // If current collectible id not in collection - ignore it
      if ($this->collectible_id !== null && in_array($this->collectible_id, $collectible_ids))
      {
        $id_key = array_search($this->collectible_id, $collectible_ids);

        // Rebuild collectible array to make current collectible id first element
        $collectible_ids = array_merge(
          array_slice($collectible_ids, $id_key),
          array_slice($collectible_ids, 0, $id_key)
        );
      }

      for ($i = 0; $i < $this->getMaxPerPage(); $i++)
      {
        if (isset($collectible_ids[$pointer + $i]))
        {
          if (isset($id_key) && ($this->getNbResults() - $pointer - $i - 1) == $id_key)
          {
            // To keep order we should get items by one
            $selectByOne = true;
          }
          $ids[] = $collectible_ids[$pointer + $i];
        }
      }

    }
    if ($selectByOne)
    {
      $this->results = new PropelObjectCollection();
      foreach ($ids as $id)
      {
        $this->results[] = FrontendCollectibleQuery::create()->filterById($id)->findOne();
      }
    }
    else
    {
      $this->results = FrontendCollectibleQuery::create()
        ->filterById($ids)
        ->useCollectionCollectibleQuery()
        ->orderByPosition()
        ->endUse()
        ->find();
    }
  }

  /**
   * Returns an array of results on the given page.
   *
   * @return array
   */
  public function getResults()
  {
    return $this->results;
  }

  /**
   * Returns an object at a certain offset.
   *
   * Used internally by {@link getCurrent()}.
   *
   * @param int $offset
   *
   * @return mixed
   */
  protected function retrieveObject($offset)
  {
    return $this->results[$offset];
  }

  public function getCollection()
  {
    return $this->collection;
  }

  public function setCollectibleId($collectible_id = null)
  {
    $this->collectible_id = $collectible_id;
  }

  public function getCollectibleId()
  {
    return $this->collectible_id;
  }

}
