<?php

class autocompleteAction extends cqAjaxAction
{
  public function execute($request)
  {
    sfConfig::set('sf_web_debug', false);

    $section = $request->getParameter('section');
    $method = 'execute' . str_replace(' ', '', ucwords(str_replace('-', ' ', $section)));

    return $this->$method($request);
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  protected function executeTags($request)
  {
    $tags = array();

    $c = new Criteria();
    $c->addSelectColumn(iceModelTagPeer::ID);
    $c->addSelectColumn(iceModelTagPeer::NAME);
    $c->add(iceModelTagPeer::IS_TRIPLE, false);
    $c->add(iceModelTagPeer::NAME,'%'.$request->getParameter('tag').'%', Criteria::LIKE);
    $c->setLimit(10);

    $stmt = iceModelTagPeer::doSelectStmt($c);
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $tags[] = array('key' => $row[1], 'value' => $row[1]);
    }

    return $this->json($tags);
  }

  protected function executeCollectors($request)
  {
    $collectors = array();

    $c = new Criteria();
    $c->addSelectColumn(CollectorPeer::ID);
    $c->addSelectColumn(CollectorPeer::USERNAME);
    $c->addSelectColumn(CollectorPeer::DISPLAY_NAME);
    $c->add(CollectorPeer::DISPLAY_NAME, '%'.$request->getParameter('tag').'%', Criteria::LIKE);
    $c->setLimit(10);

    if ('exclude' == $request->getParameter('self'))
    {
      $c->add(CollectorPeer::ID, $this->getUser()->getId(), Criteria::NOT_EQUAL);
    }

    $stmt = CollectorPeer::doSelectStmt($c);
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $collectors[] = array('key' => $row[0], 'value' => sprintf('%s (%s)', $row[2], $row[1]));
    }

    return $this->json($collectors);
  }
}
