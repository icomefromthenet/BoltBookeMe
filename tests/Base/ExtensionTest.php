<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Bolt\Tests\BoltUnitTest;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\BookMeService;
use Bolt\Application;
use Bolt\Configuration\Standard;
use Cocur\Slugify\Slugify;

/**
 * Ensure that the ExtensionName extension loads correctly.
 *
 */
class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var BookMeService
     */ 
    protected $oTestAPI;
    
    protected $app; 
    
    
    protected function handleEventPostFixtureRun()
    {
        return false;
    }  
    
    protected function setAppPaths($config)
    {
        $config->setPath('app', PHPUNIT_WEBROOT . '/app');
        $config->setPath('config', PHPUNIT_WEBROOT . '/app/config');
        $config->setPath('cache', PHPUNIT_WEBROOT . '/app/cache');
        $config->setPath('web', PHPUNIT_WEBROOT . '/web');
        $config->setPath('files', PHPUNIT_WEBROOT . '/files');
        $config->setPath('themebase', PHPUNIT_WEBROOT . '/theme/');
        $config->setPath('extensionsconfig', PHPUNIT_WEBROOT . '/config/extensions');
        $config->setPath('extensions', PHPUNIT_WEBROOT . '/extensions');
        
    }
    
    protected function makeApp()
    {
        $config = new Standard(TEST_ROOT);
        $this->setAppPaths($config);
        $config->verify();

        $bolt = new MockApp(['resources' => $config]);

        // Change the database
        $bolt['config']->set(
            'general/database',
            [
                'dbname'       => $GLOBALS['DEMO_DATABASE_SCHEMA'],
                'driver'       => $GLOBALS['DEMO_DATABASE_TYPE'],
                'password'     => $GLOBALS['DEMO_DATABASE_PASSWORD'],
                'prefix'       => 'bolt_',
                'user'         => getenv('C9_USER') == false ? $GLOBALS['DEMO_DATABASE_USER'] :getenv('C9_USER'),
                'host'         => getenv('IP') == false ? $GLOBALS['DEMO_DATABASE_HOST'] : getenv('IP'),
                'wrapperClass' => '\Bolt\Storage\Database\Connection',
                'port'         => $GLOBALS['DEMO_DATABASE_PORT'],
            ]
        );
        
        
        $bolt['session.test'] = true;
        $bolt['debug'] = false;
        $bolt['config']->set('general/canonical', 'bolt.dev');
        $bolt['slugify'] = Slugify::create();

       //$this->setAppPaths($bolt['resources']);
     
        return $bolt;
    }
    
    
    protected function getApp($boot = true)
    {
        if ($this->app) {
            return $this->app;
        }
        
         if (!$this->app) {
            $app = $this->makeApp();
         
            $app->initialize();
            
        
            if ($boot) {
                $app->boot();
            }
        }
      
         
        $aConfig = $app['extensions']->get('IComeFromTheNet/BookMe')->getDefaultConfig();
        
        $this->oTestAPI = new BookMeService($app, $aConfig);
        
        return $this->app = $app;
    }
    
    protected function setUp()
    {
       $this->handleEventPostFixtureRun();
    }

    
    
    /**
    *  Return a dateTime object
    *  Children Tests that want to bootstrap with
    *  fixed date should override this class
    *
    *  @access protected
    *  @return DateTime
    *
    */
    protected function getNow()
    {
        $oDBPlatform  = $this->getDatabaseAdapter()->getDatabasePlatform();
        $oDateType    = Type::getType(Type::DATE); 
        $sNow         = $this->getDatabaseAdapter()->fetchColumn("select date_format(NOW(),'%Y-%m-%d')  ",[],0,[]);
        
        return $oDateType->convertToPHPValue($sNow,$oDBPlatform);
    }
    
    
    protected function getTestAPI()
    {
        return $this->oTestAPI;
    }
    
    protected function getDatabaseAdapter()
    {
        return $this->getApp()->offsetGet('db');
    }
    
    protected function getCommandBus()
    {
        
        return $this->getApp()->offsetGet('bm.commandBus');
    }
    
    protected function getContainer()
    {
        return $this->getApp();
    }
    
    
    protected function getAppConfig()
    {
        return $this->getApp()
                        ->offsetGet('extensions')
                        ->get('IComeFromTheNet/BookMe')
                        ->getDefaultConfig();
    }
    
    
}
/* End of Unit Test */

