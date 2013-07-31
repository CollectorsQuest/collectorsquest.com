<?php

/**
 * A test case used for functional testing a sf1 app with sf2 components
 *
 * @author  Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
abstract class sfWebTestCase extends sfFunctionalTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::createContext();

        // remove all cache
        sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));
    }

    /**
     * @return \sfPHPUnitBrowser
     */
    protected function createClient()
    {
        return new sfPHPUnitBrowser();
    }

    /**
     * Assert that the response is a valid JSON response
     * with properly set Content Type of application/json
     *
     * @param   sfWebResponse $response
     * @param   integer $statusCode
     */
    public function assertJsonResponse(sfWebResponse $response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertEquals(
            'application/json',
            $response->getContentType(),
            $response->getHttpHeaders()
        );
    }
}
