<?php

class cqPropelPager extends sfPropelPager
{
  /**
   * @param integer $v
   * @return void
   */
  public function setNbResults($v)
  {
    $this->nbResults = $v;
  }

  /**
   * @see sfPager
   */
  public function init()
  {
    $this->results = null;

    $hasMaxRecordLimit = ($this->getMaxRecordLimit() !== false);
    $maxRecordLimit = $this->getMaxRecordLimit();

    if ($this->nbResults === 0)
    {
      $criteriaForCount = clone $this->getCriteria();
      $criteriaForCount
        ->setOffset(0)
        ->setLimit(0)
        ->clearGroupByColumns()
      ;

      $count = call_user_func(array($this->getClassPeer(), $this->getPeerCountMethod()), $criteriaForCount);

      $this->setNbResults($hasMaxRecordLimit ? min($count, $maxRecordLimit) : $count);
    }

    $criteria = $this->getCriteria()
      ->setOffset(0)
      ->setLimit(0)
    ;

    if (0 == $this->getPage() || 0 == $this->getMaxPerPage())
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

      $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
      $criteria->setOffset($offset);

      if ($hasMaxRecordLimit)
      {
        $maxRecordLimit = $maxRecordLimit - $offset;
        if ($maxRecordLimit > $this->getMaxPerPage())
        {
          $criteria->setLimit($this->getMaxPerPage());
        }
        else
        {
          $criteria->setLimit($maxRecordLimit);
        }
      }
      else
      {
        $criteria->setLimit($this->getMaxPerPage());
      }
    }
  }
}
