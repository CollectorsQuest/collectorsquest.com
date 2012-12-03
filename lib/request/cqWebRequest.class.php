<?php

class cqWebRequest extends sfWebRequest
{
  /**
   * Tracks if the request comes from a mobile device
   *
   * @var null|boolean
   */
  protected $is_mobile_browser = null;

  /*
   * width of browser screen in pixels
   * @var $browser_width integer
   */
  protected $browser_width = 0;

  /*
   * width of client screen in pixels
   * @var $screen_width integer
   */
  protected $screen_width = 0;

   /*
   * height of client screen in pixels
   * @var $screen_height integer
   */
  protected $screen_height = 0;

  /**
   * Return the request protocol
   *
   * @return    string http or https
   */
  public function getProtocol()
  {
    return $this->isSecure()
      ? 'https'
      : 'http';
  }

  /**
   * Better implementation of getRemoteAddress that will return the actual IP
   * for forwarded requests, if it was disclosed in the X-FORWARDED-FOR header
   *
   * @return    string
   */
  public function getRemoteAddress()
  {
    if (null !== $proxy_forwards = $this->getForwardedFor()) {
      return $proxy_forwards[0]; // in a non-anonymous proxy, this is the real IP
    } else {
      return parent::getRemoteAddress();
    }
  }

  public function setIsMobileBrowser($value)
  {
    $this->is_mobile_browser = $value;
  }

  /**
   * Check if the request comes from a mobile device
   *
   * @return boolean
   */
  public function isMobileBrowser()
  {
    return $this->is_mobile_browser !== null ?
      (boolean) $this->is_mobile_browser :
      (boolean) @$_SERVER['mobile'];
  }

  /*
   * Check if Lazy Load (JAIL) can be used
   * We do not want to use lazy image loading when we have:
   *  1) infinite scroll
   *  2) an Ajax request
   *  3) request comes from a mobile device
   *
   * @return boolean
   */
  public function isLazyLoadEnabled()
  {
    return !$this->isMobileBrowser() && !$this->isXmlHttpRequest() && 'all' !== $this->getParameter('show');
  }

  public function setBrowserWidth($value)
  {
    $this->browser_width = $value;
  }

  public function setScreenWidth($value)
  {
    $this->screen_width = $value;
  }

  public function setScreenHeight($value)
  {
    $this->screen_height = $value;
  }

  /**
   * Get the width of client browser in pixels
   *
   * @return integer
   */
  public function getBrowserWidth()
  {
    return $this->browser_width;
  }

  /**
   * Get the width of client browser in pixels
   *
   * @return integer
   */
  public function getScreenWidth()
  {
    return $this->screen_width;
  }

  /**
   * Get the height of client browser in pixels
   *
   * @return integer
   */
  public function getScreenHeight()
  {
    return $this->screen_height;
  }

}
