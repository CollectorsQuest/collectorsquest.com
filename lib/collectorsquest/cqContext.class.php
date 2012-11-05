<?php

/**
 * @method static cqContext getInstance()
 *
 * @method cqWebResponse getResponse()
 * @method cqWebRequest getRequest()
 * @method cqFrontWebController getController()
 * @method cqBaseUser getUser()
 */
class cqContext extends sfContext
{

  public function isHomePage()
  {
    return
      $this->getModuleName() == 'general' &&
      $this->getActionName() == 'index';
  }

  public function isCollectionsPage()
  {
    return
      $this->getModuleName() == 'collections' &&
      $this->getActionName() == 'index';
  }

  public function isBlogPage()
  {
    return
      $this->getModuleName() == '_blog' &&
      $this->getActionName() == 'index';
  }

  public function isVideoPage()
  {
    return
      ($this->getModuleName() == '_video' && $this->getActionName() == 'header') ||
      ($this->getModuleName() == '_video' && $this->getActionName() == 'footer');
  }

  public function isMarketPage()
  {
    return
      $this->getModuleName() == 'marketplace' &&
      in_array($this->getActionName(), array('index', 'holiday'));
  }

}
