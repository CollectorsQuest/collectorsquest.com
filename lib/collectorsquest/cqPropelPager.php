<?php

class cqPropelPager extends sfPropelPager
{
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

}
