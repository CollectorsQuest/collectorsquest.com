<?php

class cqWebRequest extends sfWebRequest
{

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
      $ip = $proxy_forwards[0]; // in a non-anonymous proxy, this is the real IP
    } else {
      return parent::getRemoteAddress();
    }
  }

}
