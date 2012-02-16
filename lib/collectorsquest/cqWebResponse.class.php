<?php

class cqWebResponse extends sfWebResponse
{
  /** @var string */
  private $_canonical_url = null;

  /**
   * @param  string  $url
   */
  public function setCanonicalUrl($url)
  {
    $this->_canonical_url = $url;
  }

  public function getCanonicalUrl()
  {
    return $this->_canonical_url;
  }

  /**
   * @param  Collector  $collector
   * @return void
   */
  public function addGeoMeta(Collector $collector = null)
  {
    if (null === $collector)
    {
      return;
    }

    if ($geo_cache = $collector->getProfile()->getGeoCache())
    {
      $geo_region = $geo_cache['country_iso3166'];
      if (in_array($geo_region, array('US', 'CA')))
      {
        $geo_region .= '-'. $geo_cache['state'];
      }

      $geo_placename = $geo_cache['city'];
      $geo_position = array($geo_cache['latitude'], $geo_cache['longitude']);

      $this->addMeta('geo.region', $geo_region);
      $this->addMeta('geo.placename', $geo_placename);
      $this->addMeta('geo.position', implode(';', $geo_position));
      $this->addMeta('ICBM', implode(', ', $geo_position));
    }
  }
}
