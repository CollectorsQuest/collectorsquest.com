<?php

class cqWebRequest extends sfWebRequest
{
  /**
   * Tracks if the request comes from a mobile device
   *
   * @var null|boolean
   */
  protected $is_mobile = null;

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

  public function setIsMobile($value)
  {
    $this->is_mobile = $value;
  }

  /**
   * Check if the request comes from a mobile device
   *
   * @return boolean
   */
  public function isMobile()
  {
    return $this->is_mobile !== null ?
      (boolean) $this->is_mobile :
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
    return !$this->isMobile() && !$this->isXmlHttpRequest() && 'all' !== $this->getParameter('show');
  }

}
