<?php

use Symfony\Component\DomCrawler\Crawler;

/**
 * Description of sfPHPUnitBrowser
 *
 * @author  Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 *
 * @method  \Symfony\Component\DomCrawler\Crawler get(string $uri, array $parameters, bool $changeStack) GETs a uri
 * @method  \Symfony\Component\DomCrawler\Crawler post(string $uri, array $parameters, bool $changeStack) POSTs a uri
 */
class sfPHPUnitBrowser extends sfBrowser
{

    /**
     * @param   string $uri
     * @param   string $method
     * @param   array $parameters
     * @param   boolean $changeStack
     *
     * @return  \Symfony\Component\DomCrawler\Crawler
     */
    public function call(
        $uri,
        $method = 'get',
        $parameters = array(),
        $changeStack = true
    ) {
        $browser = parent::call($uri, $method, $parameters, $changeStack);
        $crawler = new Crawler();
        $crawler->add($browser->getResponse()->getContent());

        return $crawler;
    }

    /**
     * @param   string $method
     * @param   string $uri
     * @param   array $parameters
     * @param   boolean $changeStack
     *
     * @return sfBrowser
     */
    public function rawCall(
        $method,
        $uri,
        $parameters = array(),
        $changeStack = true
    ) {
        return parent::call($uri, $method, $parameters, $changeStack);
    }

    /**
     * Return the decoded json response
     *
     * @return  array
     */
    public function parseJsonResponse()
    {
        if ('application/json' == $this->getResponse()->getContentType()) {
            $decoded = json_decode($this->getResponse()->getContent(), true);

            if (null !== $decoded) {
                return $decoded;
            }
        }

        return null;
    }
}

