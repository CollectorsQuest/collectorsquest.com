<?php

class cqStatic extends IceStatic
{
  static private $_browser = array();
  static private $_browser_recycle = array();

  static private $_memcache_clients = array();
  static private $_memcache_cache   = null;

  static private $_neo4j_client = null;

  /**
   * Get a Memcache() object
   *
   * @param  array $servers
   * @return Memcache
   */
  static public function getMemcacheClient($servers = array())
  {
    // Unique key for the $server parameter
    $key = md5(serialize($servers));

    if (!isset(self::$_memcache_clients[$key]))
    {
      $servers = array_merge(
        array(
          'cq-memcached' => array(
            'host' => 'ice-memcached', 'port' => 11211,
            'persistent' => true, 'weight' => 1
          )
        ),
        $servers
      );

      // Create the Memcache instance
      self::$_memcache_clients[$key] = new Memcache();

      foreach ($servers as $server)
      {
        $port       = isset($server['port']) ? $server['port'] : 11211;
        $persistent = isset($server['persistent']) ? $server['persistent'] : true;
        $weight     = isset($server['weight']) ? $server['weight'] : 1;
        $timeout    = isset($server['timeout']) ? $server['timeout'] : 1;

        self::$_memcache_clients[$key]->addServer($server['host'], $port, $persistent, $weight, $timeout);
      }
    }

    return self::$_memcache_clients[$key];
  }

  /**
   * Get a Basecamp() object
   *
   * @return Basecamp
   */
  static public function getBasecampClient()
  {
    $client = parent::getBasecampClient();
    $client->setBaseurl('https://collectorsquest.basecamphq.com');
    $client->setUsername('api-access');
    $client->setPassword('sYD59BhdQCZcTT');

    return $client;
  }

  /**
   * Get an Impermium() object
   *
   * @return Impermium
   */
  static public function getImpermiumClient()
  {
    include_once dirname(__FILE__).'/../vendor/Impermium.class.php';

    return new Impermium(sfConfig::get('app_credentials_impermium'), '3.0');
  }

  /**
   * Get an Defensio() object
   *
   * @return Defensio
   */
  static public function getDefensioClient()
  {
    include_once dirname(__FILE__).'/../vendor/Defensio.class.php';

    return new Defensio(sfConfig::get('app_credentials_defensio'));
  }

  /**
   * Get an IceSphinxClient object
   *
   * @param  string  $hostname
   * @param  string  $culture
   *
   * @return IceSphinxClient
   */
  static public function getSphinxClient($hostname = 'cq-sphinx', $culture = 'en_US')
  {
    return parent::getSphinxClient($hostname, $culture);
  }

  /**
   * @static
   * @return Everyman\Neo4j\Client
   */
  static public function getNeo4jClient()
  {
    if (null === self::$_neo4j_client)
    {
      $port = sfConfig::get('sf_environment') == 'stg' ? '8484' : '7474';
      self::$_neo4j_client = new Everyman\Neo4j\Client('localhost', $port);
    }

    return self::$_neo4j_client;
  }

  static public function linkify($text, $shorten = false)
  {
    preg_match("/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/i", $text, $matches);
    foreach ($matches as $match)
    {
      $url = trim($match);

      if ($shorten && $tinyurl = file_get_contents('http://tinyurl.com/api-create.php?url='. $url))
      {
        $url = $tinyurl;
      }

      $text = str_replace($url, sprintf('<a href="%1$s" rel="nofollow" target="_blank">%1$s</a>', $url), $text);
    }

    return $text;
  }

  /**
   * Clean a piece of text from unwanted HTML tags
   *
   * @param  string  $text          The text to clean
   * @param  string  $allowed_tags  See http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.3
   * @param  int     $tidy          See http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.3.5
   *
   * @return string
   */
  static public function clean($text, $allowed_tags = 'b, u, i, ul, li, strong', $tidy = 0)
  {
    include_once __DIR__.'/../vendor/htmLawed.php';

    return htmLawed(
      $text,
      array(
        'elements' => $allowed_tags,
        'safe' => 1, 'keep_bad' => 0,
        'comment' => 1, 'cdata' => 1,
        'deny_attribute' => 'on', 'tidy' => $tidy
      )
    );
  }

  /**
   * @static
   * @param  integer  $limit
   * @param  array    $keywords
   *
   * @return array|null
   */
  public static function getAmazonProducts($limit = 5, $keywords = array())
  {
    require_once __DIR__.'/../vendor/tarzanaws/tarzan.class.php';

    $products = array();
    $keywords = (is_array($keywords)) ? implode(' ', $keywords) : trim($keywords);

    $cache = new sfMemcacheCache(array('memcache' => cqStatic::getMemcacheClient()));

    $key = md5($limit .'-'. serialize($keywords));
    if ($products = $cache->get($key))
    {
      return $products;
    }

    // Muting Tarzan errors
    $error_reporting = error_reporting(0);

    try
    {
      $amazon = new AmazonAAWS();
      $results = $amazon->item_search(
        $keywords,
        array(
          'ResponseGroup' => 'Medium',
          'SearchIndex' => 'Blended',
          'Keywords' => $keywords
        )
      );

      $i = 0;
      foreach ($results->body->Items->Item as $product)
      {
        if (!isset($product->OfferSummary->LowestNewPrice))
        {
          continue;
        }

        $products[(string) $product->ASIN] = array(
          'title' => str_replace('/', ' / ', (string) $product->ItemAttributes->Title),
          'url' => (string) $product->DetailPageURL,
          'image' => is_object($product->SmallImage) ? (string) $product->SmallImage->URL : '',
          'price' => (string) $product->OfferSummary->LowestNewPrice->FormattedPrice,
          'total' => (int) $product->OfferSummary->TotalNew
        );

        if (empty($products[(string) $product->ASIN]['image']))
        {
          unset($products[(string) $product->ASIN]);
          continue;
        }

        if (++$i >= $limit) break;
      }
    }
    catch (TarzanHTTPRequest_Exception $e) { ;	}

    $cache->set($key, $products, 86400);

    // Restoring...
    error_reporting($error_reporting);

    return $products;
  }

  /**
   * Fetch a page by the given $url and return the sfWebBrowser object
   *
   * @param  string   $url
   * @param  string   $encoding
   * @param  integer  $timeout
   * @param  integer  $retries
   * @param  array    $options
   *
   * @return  sfWebBrowser | null
   */
  public static function fetch($url, $encoding = 'utf-8', $timeout = 30, $retries = 3, $options = array())
  {
    $b = self::getBrowser($encoding, $timeout, $options);

    do
    {
      try
      {
        $b->get($url);
      }
      catch (Exception $e)
      {
        ;
      }
    }
    while (--$retries > 0 && $b->getResponseCode() != 200);

    if (200 == $b->getResponseCode())
    {
      return $b;
    }

    return null;
  }

  /**
   * Get a sfWebBrowser instance
   *
   * @param  string   $encoding
   * @param  integer  $timeout
   * @param  array    $options
   *
   * @return sfWebBrowser
   */
  static public function getBrowser($encoding = 'UTF-8', $timeout = 30, $options = array())
  {
    $hash = md5(serialize($options) . $encoding . $timeout);

    if (!isset($options['recycle']))            $options['recycle'] = false;
    if (!isset(self::$_browser_recycle[$hash])) self::$_browser_recycle[$hash] = 0;

    if (empty(self::$_browser[$hash]) || ++self::$_browser_recycle[$hash] > 10 || $options['recycle'] == true)
    {
      self::$_browser_recycle[$hash] = 0;
      unset($options['recycle']);

      $user_agents = file(sfConfig::get('sf_data_dir').'/user_agents.txt');
      $agent  = trim($user_agents[array_rand($user_agents)]);
      $options = array_merge(
        array(
          'cookies'        => true,
          'cookies_dir'    => sprintf('%s/sfWebBrowser', sfConfig::get('sf_cache_dir')),
          'cookies_file'   => sprintf('%s/sfWebBrowser/%s.txt', sfConfig::get('sf_cache_dir'), uniqid('cookies_', true)),
          'USERAGENT'      => $agent,
          'TIMEOUT'        => $timeout,
          'SSL_VERIFYPEER' => false
        ),
        $options
      );

      self::$_browser[$hash] = new sfWebBrowser(array(), 'sfCurlAdapter', $options);
    }

    return self::$_browser[$hash];
  }

}
