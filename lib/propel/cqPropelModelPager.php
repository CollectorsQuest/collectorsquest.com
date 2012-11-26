<?php

class cqPropelModelPager extends PropelModelPager
{
  protected $nbResults = null;

  /**
   * @var bool
   */
  private $strictMode = false;

  /**
   * @param bool $strictMode
   */
  public function setStrictMode($strictMode)
  {
    $this->strictMode = $strictMode;
  }

  /**
   * @return bool
   */
  public function getStrictMode()
  {
    return $this->strictMode;
  }

  public function setNbResults($nb)
  {
    parent::setNbResults($nb);
  }

  /**
   * Sets the last page number.
   *
   * @param integer $page
   */
  protected function setLastPage($page)
  {
    $this->lastPage = $page;

    if ($this->getStrictMode() && $this->getPage() > $page)
    {
      $this->setPage($page);
    }
  }

  public function asRssFeed($route, sfRssFeed $feed = null)
  {
    $feed = $feed ?: new sfRssFeed();

    if (!$feed->getTitle())
    {
      $feed->initialize(array(
        'title'       => 'Collectors Quest',
        'link'        => 'http://www.collectorsquest.com/',
        'authorEmail' => 'info@collectorsquest.com',
        'authorName'  => 'Collectors Quest, Inc'
      ));
    }

    $items = sfFeedPeer::convertObjectsToItems(
      $this->getResults(), array('routeName' => $route)
    );
    $feed->addItems($items);

    return $feed->asXml(ESC_RAW);
  }


	public function init($con = null)
	{
		$this->con = $con;
		$hasMaxRecordLimit = ($this->getMaxRecordLimit() !== false);
		$maxRecordLimit = $this->getMaxRecordLimit();

    if (null === $this->getNbResults())
    {
      $qForCount = clone $this->getQuery();
      $count = $qForCount
        ->offset(0)
        ->limit(0)
        ->count($this->con);

      $this->setNbResults($hasMaxRecordLimit ? min($count, $maxRecordLimit) : $count);
    }

		$q = $this->getQuery()
			->offset(0)
			->limit(0);

		if (($this->getPage() == 0 || $this->getMaxPerPage() == 0)) {
			$this->setLastPage(0);
		} else {
			$this->setLastPage((int)ceil($this->getNbResults() / $this->getMaxPerPage()));

			$offset = ($this->getPage() - 1) * $this->getMaxPerPage();
			$q->offset($offset);

			if ($hasMaxRecordLimit) {
				$maxRecordLimit = $maxRecordLimit - $offset;
				if ($maxRecordLimit > $this->getMaxPerPage()) {
					$q->limit($this->getMaxPerPage());
				} else {
					$q->limit($maxRecordLimit);
				}
			} else {
				$q->limit($this->getMaxPerPage());
			}
		}
	}

}
