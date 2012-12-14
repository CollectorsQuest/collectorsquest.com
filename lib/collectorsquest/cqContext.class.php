<?php

/**
 * @method cqWebResponse getResponse()
 * @method cqWebRequest getRequest()
 * @method cqFrontWebController getController()
 * @method cqBaseUser getUser()
 */
class cqContext extends sfContext
{

  /**
   * @see sfContext::createInstance()
   */
  static public function createInstance(sfApplicationConfiguration $configuration, $name = null, $class = __CLASS__)
  {
    /* @var $context cqContext */
    $context = parent::createInstance($configuration, $name, $class);

    return $context;
  }

  /**
   * @see sfContext::getInstance()
   */
  static public function getInstance($name = null, $class = __CLASS__)
  {
    /* @var $context cqContext */
    $context = parent::getInstance($name, $class);

    return $context;
  }

  /**
   * @see sfContext::hasInstance()
   */
  public static function hasInstance($name = null)
  {
    return parent::hasInstance($name);
  }

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
