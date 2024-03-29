<?php

class cqWebRequest extends sfWebRequest
{
  /**
   * Tracks if the request comes from a mobile device
   *
   * @var null|boolean
   */
  protected $is_mobile_browser = null;

  /**
   * Width of browser screen in pixels
   *
   * @var $browser_width integer
   */
  protected $browser_width = 0;

  /**
   * Width of browser screen in pixels
   *
   * @var $browser_width integer
   */
  protected $browser_height = 0;

  /**
   * Width of client screen in pixels
   *
   * @var $screen_width integer
   */
  protected $screen_width = 0;

   /**
   * Height of client screen in pixels
   *
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
    if (null !== $proxy_forwards = $this->getForwardedFor())
    {
      return $proxy_forwards[0]; // in a non-anonymous proxy, this is the real IP
    }
    else
    {
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
    $this->browser_width = (int) $value;
  }

  public function setBrowserHeight($value)
  {
    $this->browser_height = (int) $value;
  }

  public function setScreenWidth($value)
  {
    $this->screen_width = (int) $value;
  }

  public function setScreenHeight($value)
  {
    $this->screen_height = (int) $value;
  }

  /**
   * Get the width of client browser in pixels
   *
   * @return integer
   */
  public function getBrowserWidth()
  {
    if ($this->browser_width == 0)
    {
      $this->parseResolutionCookie();
    }

    return $this->browser_width;
  }

  /**
   * Get the width of client browser in pixels
   *
   * @return integer
   */
  public function getScreenWidth()
  {
    if ($this->screen_width == 0)
    {
      $this->parseResolutionCookie();
    }

    return $this->screen_width;
  }

  /**
   * Get the height of client browser in pixels
   *
   * @return integer
   */
  public function getScreenHeight()
  {
    if ($this->screen_height == 0)
    {
      $this->parseResolutionCookie();
    }

    return $this->screen_height;
  }

  /**
   * Parse the resolution cookie and set browser and screen sizes
   */
  public function parseResolutionCookie()
  {
    // in the cookie set in application.js we have 4 values divided by 'x'
    $cookie_data = explode('x', $this->getCookie('cq_resolution'));

    $this->setScreenWidth(isset($cookie_data[0]) ? $cookie_data[0] : 1024);
    $this->setScreenHeight(isset($cookie_data[1]) ? $cookie_data[1] : 768);

    $this->setBrowserWidth(isset($cookie_data[2]) ? $cookie_data[2] : 1024);
    $this->setBrowserHeight(isset($cookie_data[3]) ? $cookie_data[3] : 696);
  }

  /**
   * Check if the client is on a mobile device with a small screen
   *
   * @return boolean
   */
  public function isMobileLayout()
  {
    return $this->isMobileBrowser() && $this->getBrowserWidth() < 1024;
  }

  /**
   * Check if the client is on a mobile device with a screen smaller than 1024
   * so it can fit the desktop website version
   *
   * @return boolean
   */
  public function isDesktopLayout()
  {
    return $this->isMobileBrowser() && $this->getBrowserWidth() > 1024;
  }

}
