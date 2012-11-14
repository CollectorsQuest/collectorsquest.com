<?php
/**
 * Pager for Collection Collectibles Carousel Widget
 */

class cqCollectionCollectiblesPager extends sfPager
{
  /* @var Collection */
  protected $collection = null;

  /* @var null|PropelCollection */
  protected $results = null;

  /* @var null|integer */
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

    // Get all public collectibles IDs related to collection and sorted by position
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

    /* @var $ids array of collectibles Ids for current page*/
    $ids = array();

    if (!$this->haveToPaginate())
    {
      $ids = $collectible_ids;
    }
    else
    {

      /* @var $pointer integer - point to first item on page */
      $pointer = (
        (
          $this->getPage() <= $this->getLastPage() ? $this->getPage() : $this->getLastPage()
        )
          - 1) * $this->getMaxPerPage();

      // If current collectible id not in collection - ignore it
      if ($this->collectible_id !== null && in_array($this->collectible_id, $collectible_ids))
      {
        $id_key = array_search($this->collectible_id, $collectible_ids);

        // Rebuild collectibles IDs array to make current collectible id as first element
        $collectible_ids = array_merge(
          array_slice($collectible_ids, $id_key),
          array_slice($collectible_ids, 0, $id_key)
        );
      }

      for ($i = 0; $i < $this->getMaxPerPage(); $i++)
      {
        // Get other page items
        if (isset($collectible_ids[$pointer + $i]))
        {
          // If collectible_ids array was rebuilt, then for docking page sort order by position will be wrong
          if (isset($id_key) && ($this->getNbResults() - $pointer - $i - 1) == $id_key)
          {
            // To fix it we should get items by one, it save array of Ids sort order
            $selectByOne = true;
          }
          $ids[] = $collectible_ids[$pointer + $i];
        }
      }

    }

    // Fetching result from Ids array
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

  /**
   * Get Cellection
   *
   * @return Collection
   */
  public function getCollection()
  {
    return $this->collection;
  }

  /**
   * Set current collectible id
   *
   * @param null $collectible_id
   * @return cqCollectionCollectiblesPager
   */
  public function setCollectibleId($collectible_id = null)
  {
    $this->collectible_id = (integer) $collectible_id;
    return $this;
  }

  /**
   * Get current collectible id
   *
   * @return int|null
   */
  public function getCollectibleId()
  {
    return $this->collectible_id;
  }

}
