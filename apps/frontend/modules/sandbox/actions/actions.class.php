<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage general
 * @author     Collectors
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sandboxActions extends cqFrontendActions
{

  public function executeIndex()
  {
    return sfView::NONE;
  }

  public function executeSidebar300()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar160()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar180()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar120()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarnone()
  {
    return sfView::SUCCESS;
  }

  public function executeNocollectionhelp()
  {
    return sfView::SUCCESS;
  }

  public function executeComments()
  {
    return sfView::SUCCESS;
  }

  public function executeSearch()
  {
    return sfView::SUCCESS;
  }

  public function executeAccordion()
  {
    return sfView::SUCCESS;
  }

}
