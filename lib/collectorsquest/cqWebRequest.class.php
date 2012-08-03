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

}
