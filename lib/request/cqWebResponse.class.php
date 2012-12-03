<?php

class cqWebResponse extends IceWebResponse
{
  /* @var string */
  protected $_context = 'collectorsquest';

  /* @var string */
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

  public function addOpenGraphMeta($name, $value)
  {
    // By default, escape the tag value
    $escape = true;

    if (is_array($value))
    {
      $value = serialize($value);
      $escape = false;
    }

    $this->addMeta('og:'. $name, $value, false, $escape);
  }

  public function addOpenGraphMetaFor(BaseObject $object)
  {
    /** @var cqApplicationConfiguration $configuration */
    $configuration = sfProjectConfiguration::getActive();
    $configuration->loadHelpers(array('cqLinks', 'cqImages'));

    switch (get_class($object))
    {
      // Special case for WordPress Posts (custom post types mainly)
      case 'wpPost':
        /** @var $object wpPost */

        // The OpenGraph title/description has priority over the SEO meta title/description
        $title = $object->getPostMetaValue('_yoast_wpseo_title') ?:
                 $object->getPostTitle();
        $description = $object->getPostMetaValue('_yoast_wpseo_opengraph-description') ?:
                       $object->getPostMetaValue('_yoast_wpseo_metadesc');

        $this->addOpenGraphMeta('url', $this->getCanonicalUrl() ?: cq_url_for($object, true));
        $this->addOpenGraphMeta('title', $title);
        $this->addOpenGraphMeta('description', $description);
        $this->addOpenGraphMeta('image', $object->getPostThumbnail('original'));
        $this->addOpenGraphMeta('type', 'collectorsquest:'. $object->getPostType());
        break;
      default:
        // CollectionCollectibles need to be normalized to Collectibles
        if ($object instanceof CollectionCollectible)
        {
          /** @var $object CollectionCollectible */
          $object = $object->getCollectible();
        }

        $this->addOpenGraphMeta('title', (string) $object .' | Collectors Quest');
        $this->addOpenGraphMeta('url', $this->getCanonicalUrl() ?: cq_url_for($object, true));

        if (method_exists($object, 'getDescription'))
        {
          $this->addOpenGraphMeta('description', (string) $object->getDescription('stripped', 300));
        }

        /** @var PropelObjectCollection */
        $multimedia = $object->getMultimedia(0, 'image');

        if (count($multimedia))
        {
          $images = array();
          foreach ($multimedia as $m)
          {
            $images[] = src_tag_multimedia($m, 'original');
          }

          $this->addOpenGraphMeta('image', $images);
        }

        // Infer the og:type from the class name
        $type = sfInflector::underscore(get_class($object));

        /**
         * We want to simplify to "Collector", "Collection", "Collectible"
         */
        if ($type === 'collection_collectible')
        {
          $type = 'collectible';
        }
        else if ($type === 'collector_collection')
        {
          $type = 'collection';
        }

        $this->addOpenGraphMeta(
          'type', 'collectorsquest:'. $type
        );
        break;
    }

  }

}
