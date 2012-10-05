<?php

require_once dirname(__FILE__).'/../lib/multimediaGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/multimediaGeneratorHelper.class.php';

/**
 * multimedia actions.
 *
 * @package    CollectorsQuest
 * @subpackage multimedia
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class multimediaActions extends autoMultimediaActions
{
  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeCrop(sfWebRequest $request)
  {
    /* @var $multimedia iceModelMultimedia */
    $multimedia = $this->getRoute()->getObject();

    //return sfView::ERROR;
  }
}
