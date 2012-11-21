<?php

// @todo add method to reset sitemap and data in .txt files so a new sitemap can be generated

class batchGenerateSitemapTask extends sfBaseTask
{
  /* @var SimpleXMLElement */
  protected $sitemap_index = null;

  /* @var null|integer */
  protected $month = null;

  /* @var null|integer */
  protected $start_date = null;

  /* @var null|integer */
  protected $end_date = null;

  /* @var sfApplicationConfiguration */
  protected $configuration;

  /*
   * set limit for records processed on a single iteration
   *
   * @var integer
   */
  protected $limit = 2000;

  protected function configure()
  {
    unset($_SERVER['PATH_TRANSLATED'], $_SERVER['SCRIPT_NAME']);

    $this->namespace  = 'batch';
    $this->name       = 'generate-sitemap';

    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $_SERVER['HTTP_HOST'] = sfConfig::get('app_www_domain');

    cqContext::createInstance($this->configuration);

    // Database initialization
    new sfDatabaseManager($this->configuration);

    // For the sitemap we can easily use the slave servers
    Propel::setForceMasterConnection(false);

    /**
     * Get a read-only connection
     *
     * @var $connection PropelPDO
     */
    $connection = Propel::getConnection('propel', Propel::CONNECTION_READ);

    // Load the Links helper
    $this->configuration->loadHelpers('cqLinks');

    // we can easily process the first 2 in one iteration and just add a check it is done
    // $this->_landing_pages();
    // $this->_content_categories($connection);
    $this->_collectors($connection);
    // $this->_collections($connection);
    // $this->_collectibles($connection);

    // Write the sitemap index file
    if ($this->sitemap_index instanceof SimpleXMLElement)
    {
      $dom = dom_import_simplexml($this->sitemap_index)->ownerDocument;
      $dom->formatOutput = true;
      $dom->save(sfConfig::get('sf_web_dir') .'/sitemap.xml', LIBXML_NOEMPTYTAG);
      $sitemap_url = 'http://'. sfConfig::get('app_www_domain').'/sitemap.xml';

      // Ping search engines
      /*
       * @ todo uncomment when functionality is ready and we have added all items
      file('http://www.google.com/webmasters/sitemaps/ping?sitemap='. $sitemap_url);
      file('http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='. $sitemap_url);
      file('http://webmaster.live.com/webmaster/ping.aspx?siteMap='. $sitemap_url);
      file('http://submissions.ask.com/ping?sitemap='. $sitemap_url);
      */
    }
  }

  /**
   * Collectors
   *
   * @param  PropelPDO  $connection
   * @return void
   */
  private function _collectors(PropelPDO $connection = null)
  {
    /** @var $q CollectorQuery */
    $q = CollectorQuery::create()
      ->orderBy('Collector.CreatedAt', 'DESC')
      ->filterByIsPublic(true);

    $collector_ids = $q->select('Id')->find($connection)->toArray();

    /** @var $q CollectorQuery */
    $q = CollectorQuery::create()
      ->orderBy('Collector.CreatedAt', 'DESC')
      ->filterByIsPublic(true);

    $old_collector_ids = file_get_contents(sfConfig::get('sf_web_dir') . '/sitemaps/collectors.txt');
    if ($old_collector_ids != '')
    {
      $old_collector_ids = explode(",", trim($old_collector_ids));
      $new_collector_ids = array_diff($collector_ids, $old_collector_ids);

      /** @var $collectors Collector[] */
      $collectors = $q->filterById($new_collector_ids, Criteria::IN)->limit($this->limit)->find($connection);
    }
    else
    {
      $old_collector_ids = array();

      /** @var $collectors Collector[] */
      $collectors = $q->limit($this->limit)->find($connection);
    }

    $added_collector_ids = array();

    if (!empty($collectors))
    {
      $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/collectors_'. time() . '.xml';
      $writer = $this->getWriter($sitemap);

      $max = count($collectors);
      for ($i = 0; $i < $max; $i++)
      {
        if ($this->limit > 0)
        {
          $writer->startElement('url');
            $writer->writeElement('loc', url_for_collector($collectors[$i], true));
            $writer->writeElement('changefreq', 'weekly');
            $writer->writeElement('priority', '0.4');

            /**
             * @see http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=178636
             */
            $writer->startElement('image:image');
              $writer->writeElement('image:loc', src_tag_collector($collectors[$i], '235x315'));
              $writer->writeElement('image:caption', $collectors[$i]->getDisplayName());
              $writer->writeElement('image:title', 'Collectors Quest Collector - ' . $collectors[$i]->getDisplayName());
            $writer->endElement(); //image

          $writer->endElement();

          $this->limit--;
          $added_collector_ids[] = $collectors[$i]->getId();
        }
      }

      $added_collector_ids = array_merge($added_collector_ids, $old_collector_ids);
      file_put_contents(sfConfig::get('sf_web_dir') . '/sitemaps/collectors.txt', implode(',', $added_collector_ids));

      $this->flushWriter($writer);
      $this->addSitemap($sitemap);
    }
  }

  /**
   * Collections
   *
   * @param  PropelPDO  $connection
   * @return void
   */
  private function _collections(PropelPDO $connection = null)
  {
    /** @var $q CollectorCollectionQuery */
    $q = CollectorCollectionQuery::create()
      ->orderBy('CollectorCollection.CreatedAt', 'DESC')
      ->filterByIsPublic(true);

    /** @var $collections CollectorCollection[] */
    $collections = $q->find($connection);

    if (!empty($collections))
    {
      $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/collections.xml';
      $writer = $this->getWriter($sitemap);

      foreach ($collections as $collection)
      {
        $writer->startElement('url');
          $writer->writeElement('loc', url_for_collection($collection, true));
          $writer->writeElement('changefreq', 'weekly');
          $writer->writeElement('priority', '0.6');

          /**
           * @see http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=178636
           */
          $writer->startElement('image:image');
            $writer->writeElement('image:loc', src_tag_collection($collection, '190x190'));
            $writer->writeElement('image:caption', $collection->getName());
            $writer->writeElement('image:title', 'Collectors Quest Collection - ' . $collection->getName());
          $writer->endElement(); // image

        $writer->endElement();
      }

      $this->flushWriter($writer);
      $this->addSitemap($sitemap);
    }
  }

  /**
   * Collectibles
   *
   * @param  PropelPDO  $connection
   * @return void
   */
  private function _collectibles(PropelPDO $connection = null)
  {
    /** @var $q CollectibleQuery */
    $q = CollectibleQuery::create()
      ->orderBy('Collectible.CreatedAt', 'DESC')
      ->filterByIsPublic(true);

    /** @var $collectibles Collectible[] */
    $collectibles = $q->find($connection);

    //declare variables that will be overwritten later
    $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/collectibles.xml';
    $writer = $this->getWriter($sitemap);

    if (!empty($collectibles))
    {
      /**
       * used to create separate sitemap files as there is 50 000 entries limit
       *
       * @var $i integer
       */
      $i = 0;
      foreach ($collectibles as $collectible)
      {
        if ($i % 20000 == 0)
        {
          $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/collectibles_' . $i / 20000 . '.xml';
          $writer = $this->getWriter($sitemap);
        }

        $is_for_sale = $collectible->isForSale();

        $writer->startElement('url');
          $writer->writeElement('loc', url_for_collectible($collectible, true));
          $writer->writeElement('changefreq', 'weekly');
          $writer->writeElement('priority', $is_for_sale ? '0.6' : '0.5');

          /**
           * @see http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=178636
           */
          $writer->startElement('image:image');
            $writer->writeElement('image:loc', src_tag_collectible($collectible, '620x0'));
            $writer->writeElement('image:caption', $collectible->getName());
            $title_string = $is_for_sale ? 'Item for Sale' : 'Collectible';
            $writer->writeElement(
              'image:title', sprintf('Collectors Quest %s - %s', $title_string, $collectible->getName())
            );
          $writer->endElement(); // image

        $writer->endElement();

        if ($i % 20000 == 19999)
        {
          $this->flushWriter($writer);
          $this->addSitemap($sitemap);
        }

        $i++;
      }

      $this->flushWriter($writer);
      $this->addSitemap($sitemap);
    }
  }

  /**
   * Content Categories
   *
   * @param  PropelPDO  $connection
   * @return void
   */
  private function _content_categories(PropelPDO $connection = null)
  {
    /** @var $q ContentCategoryQuery */
    $q = ContentCategoryQuery::create();

    /** @var $content_categories ContentCategory[] */
    $content_categories = $q->find($connection);

    if (!empty($content_categories))
    {
      $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/content_categories.xml';
      $writer = $this->getWriter($sitemap);

      foreach ($content_categories as $category)
      {
        $writer->startElement('url');
        $writer->writeElement('loc', url_for('content_category', $category, true));
        $writer->writeElement('changefreq', 'weekly');
        $writer->writeElement('priority', '0.7');

        $writer->endElement();
      }

      $this->flushWriter($writer);
      $this->addSitemap($sitemap);
    }
  }

  /**
   * Static Pages
   *
   * @return void
   */
  private function _landing_pages()
  {
    $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/landing_pages.xml';
    $writer = $this->getWriter($sitemap);

    //routes with daily changes and 1 priority (Main Pages)
    $daily_routes_highest = array('@blog', '@video', '@marketplace');

    //routes with weekly changes and 0.9 priority (Sub Pages / Product  Listings)
    $daily_routes = array(
      '@aetn_american_pickers', '@aetn_pawn_stars', '@aetn_picked_off', '@aetn_american_restoration',
      '@collections', '@collectors', '@collectors?sort=most-popular'
    );

    //routes with weekly changes and 0.9 priority (Sub Pages / Product  Listings)
    $weekly_routes = array('@content_categories', '@marketplace_categories');

    //routes with weekly changes and 0.5 priority (Resource Pages)
    $weekly_routes_resource = array(
      '@misc_guide_to_collecting', '@misc_guide_to_collecting_shortcut', '@misc_guide_download'
    );

    foreach ($daily_routes_highest as $route)
    {
      $writer->startElement('url');
      $writer->writeElement('loc', url_for($route, true));
      $writer->writeElement('changefreq', 'daily');
      $writer->writeElement('priority', '1.0');

      $writer->endElement();
    }

    foreach ($daily_routes as $route)
    {
      $writer->startElement('url');
      $writer->writeElement('loc', url_for($route, true));
      $writer->writeElement('changefreq', 'daily');
      $writer->writeElement('priority', '0.9');

      $writer->endElement();
    }

    foreach ($weekly_routes as $route)
    {
      $writer->startElement('url');
      $writer->writeElement('loc', url_for($route, true));
      $writer->writeElement('changefreq', 'weekly');
      $writer->writeElement('priority', '0.9');

      $writer->endElement();
    }

    foreach ($weekly_routes_resource as $route)
    {
      $writer->startElement('url');
      $writer->writeElement('loc', url_for($route, true));
      $writer->writeElement('changefreq', 'weekly');
      $writer->writeElement('priority', '0.5');

      $writer->endElement();
    }

    $this->flushWriter($writer);
    $this->addSitemap($sitemap);
  }

  private function addSitemap($sitemap)
  {
    if (!($this->sitemap_index instanceof SimpleXmlElement))
    {
      $this->sitemap_index = simplexml_load_file(sfConfig::get('sf_web_dir').'/sitemap.xml', 'IceXMLElement');
    }

    if (substr($sitemap, -3) != '.gz')
    {
      exec('/bin/gzip -q -f '. $sitemap);
      $sitemap = $sitemap .'.gz';
    }

    $present = false;
    foreach ($this->sitemap_index->sitemap as $url)
    {
      if (basename((string) $url->loc) == basename($sitemap))
      {
        $url->lastmod = date_format(new DateTime(), DATE_W3C);
        $present = true;
      }
    }
    if ($present == false)
    {
      $url = $this->sitemap_index->addChild('sitemap');
      $url->addChild('loc', 'http://'. sfConfig::get('app_www_domain') . '/sitemaps/' . basename($sitemap));
      $url->addChild('lastmod', date_format(new DateTime(), DATE_W3C));
    }
  }

  private function getWriter($filename)
  {
    $writer = new XMLWriter();

    $writer->openURI($filename);
    $writer->startDocument('1.0', 'utf-8');
    $writer->setIndent(true);
    $writer->setIndentString('  ');

    // declare it as an rss document
    $writer->startElement('urlset');
    $writer->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $writer->writeAttribute(
      'xsi:schemaLocation',
      'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'
    );
    $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    $writer->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

    return $writer;
  }

  private function flushWriter(XMLWriter $writer)
  {
    $writer->endElement();
    $writer->endDocument();

    $writer->flush();
  }
}
