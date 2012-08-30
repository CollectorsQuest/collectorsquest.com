<?php

require_once dirname(__FILE__) . '/../lib/messagesGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/messagesGeneratorHelper.class.php';

/**
 * messages actions.
 *
 * @package    CollectorsQuest
 * @subpackage messages
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class messagesActions extends autoMessagesActions
{
  protected function processSort($query)
  {
    $sort = $this->getSort();
    if (array(null, null) == $sort) {
      return;
    }

    if (in_array($sort[0], array('sender_name','reseiver_name'))) {
       $column = sfInflector::camelize($sort[0]);

    } else {
      try {
        $column = PrivateMessagePeer::translateFieldName($sort[0], BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_PHPNAME);
      } catch (PropelException $e) {
        // probably a fake column, using a custom orderByXXX() query method
        $column = sfInflector::camelize($sort[0]);
      }
    }
    $method = sprintf('orderBy%s', $column);

    try {
      $query->$method('asc' == $sort[1] ? 'asc' : 'desc');
    } catch (PropelException $e) {
      // non-existent sorting method
      // ignore the sort parameter
      $this->setSort(array(null, null));
    }
  }
}
