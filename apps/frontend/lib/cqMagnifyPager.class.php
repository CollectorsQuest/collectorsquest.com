<?php
/**
 * Copyright 2012 Collectors Quest, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * Filename: cqMagnifyPager.class.php
 *
 * Put some description here
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 4/5/12
 * Id: $Id$
 */

class cqMagnifyPager extends sfPager
{
  private $query;

  public function __construct($query, $maxPerPage = 12)
  {
    parent::__construct(null, $maxPerPage);

    $this->query = $query;
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
    $magnify = cqStatic::getMagnifyClient();
    $sort = $this->getParameter('sort', 'recent');

    $this->results = $magnify->getContent()->find($this->query, $this->getPage(), $this->getMaxPerPage(), $sort);

    $this->setNbResults($this->results->getTotalResults());

    if (0 == $this->getPage() || 0 == $this->getMaxPerPage())
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
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

}
