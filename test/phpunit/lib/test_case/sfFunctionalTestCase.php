<?php

require_once get_root_dir() . '/config/ProjectConfiguration.class.php';

/**
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
abstract class sfFunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    const APPLICATION = 'frontend';

    /** @var sfProjectConfiguration */
    protected static $configuration;
    /** @var sfDatabaseManager */
    protected static $database_manager;
    /** @var sfContext */
    protected static $context;

    public static function setUpBeforeClass()
    {
        // we need to initialize the configuration to an application
        // because otherwize we don't get the use of enviroments,
        // and we need that for the proper settings from the database.yml to be loaded
        static::$configuration = ProjectConfiguration::getApplicationConfiguration(
            static::APPLICATION,
            'test',
            true // debug
        );
        static::$database_manager = new sfDatabaseManager(static::$configuration);
    }

    /**
     * @return  sfContext
     */
    public static function createContext()
    {
        if (null === static::$context) {
            // create symfony context
            static::$context = sfContext::createInstance(static::$configuration);
        }

        return static::$context;
    }

    public static function loadFixtureDirs($dirs, PropelPDO $con = null)
    {
        if (is_array($dirs)) {
            foreach ($dirs as $key => $dir) {
                $dirs[$key] = sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $dir;
            }
        } elseif (is_string($dirs)) {
            $dirs = sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $dirs;
        } else {
            throw new RuntimeException(sprintf(
                '[Model Unit Test] load_fixture_dirs requires the $dirs parameter
                to be either array or a string'
            ));
        }

        if (is_null($con)) {
            $con = Propel::getConnection();
        }

        $con->prepare('SET FOREIGN_KEY_CHECKS = 0;')->execute();

        // load fixtures; this cleans the database too
        $loader = new sfPropelData();
        $loader->loadData($dirs);

        $con->prepare('SET FOREIGN_KEY_CHECKS = 1;')->execute();
    }

}
