<?php

class batchGenerateSitemapTask extends sfBaseTask
{
  private
    $total = 8,
    $i = 0;

  /** @var SimpleXMLElement */
  protected $sitemap_index = null;

  protected
    $month = null,
    $start_date = null,
    $end_date = null;

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
    sfContext::createInstance($this->configuration);

    // Database initialization
    $databaseManager = new sfDatabaseManager($this->configuration);

    // For the sitemap we can easily use the slave servers
    Propel::setForceMasterConnection(false);

    // Get a read-only connection
    $connection = Propel::getConnection('propel', Propel::CONNECTION_READ);

    // Load the Links helper
    $this->configuration->loadHelpers('cqLinks');

    $this->_collectors($connection);

    // Write the sitemap index file
    if ($this->sitemap_index instanceof SimpleXMLElement)
    {
      $dom = dom_import_simplexml($this->sitemap_index)->ownerDocument;
      $dom->formatOutput = true;
      $dom->save(sfConfig::get('sf_web_dir') .'/sitemap.xml', LIBXML_NOEMPTYTAG);
      $sitemap_url = 'http://'. sfConfig::get('app_www_domain').'/sitemap.xml';

      // Ping search engines
      file('http://www.google.com/webmasters/sitemaps/ping?sitemap='. $sitemap_url);
      file('http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='. $sitemap_url);
      file('http://webmaster.live.com/webmaster/ping.aspx?siteMap='. $sitemap_url);
      file('http://submissions.ask.com/ping?sitemap='. $sitemap_url);
    }

    //$this->progress(++$this->i, $this->total);
  }

  /**
   * Collectors
   *
   * @param  PropelPDO  $connection
   * @return void
   */
  private function _collectors(PropelPDO $connection = null)
  {
    $q = CollectorQuery::create()
      ->orderBy('Collector.CreatedAt', 'DESC');
    $collectors = $q->find($connection);

    /**
     * Avoid generation of empty sitemap files (for the months and dates there is no data)
     */
    if (!empty($collectors))
    {
      $sitemap = sfConfig::get('sf_web_dir') . '/sitemaps/collectors/collectors.xml';
      $writer = $this->getWriter($sitemap);

      $i = 0;
      foreach ($collectors as $collector)
      {
        // for testing purposes - reduce execution time
        if ($i < 50)
        {
          $writer->startElement('url');
          $writer->writeElement('loc', link_to_collector($collector, 'text'));
          $writer->writeElement('changefreq', 'weekly');
          $writer->writeElement('priority', '0.9');

          $writer->endElement();

          /**
           * @see http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=178636
           */

          $writer->startElement("image:image");
          $writer->writeElement('image:loc', link_to_collector($collector, 'image'));

          $writer->endElement();
        }
        else
        {
          break;
        }
        $i++;
      }

      $this->flushWriter($writer);
      $this->addSitemap($sitemap, 'collectors');
    }

    //$this->progress(++$this->i, $this->total);
  }

  private function addSitemap($sitemap, $path)
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
      $url->addChild('loc', 'http://'. sfConfig::get('app_www_domain') . '/sitemaps/' . $path . '/'. basename($sitemap));
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
    $writer->writeAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
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
