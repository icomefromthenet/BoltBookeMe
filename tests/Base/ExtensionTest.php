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
        $config->setPath('web', PHPUNIT_WEBROOT . '/');
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

        $bolt = new Application(['resources' => $config]);

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

        $this->setAppPaths($bolt['resources']);
     
        return $bolt;
    }
    
    protected function getAppConfig()
    {
        return [
            'apptnumber' => [
                 'suffix'       => '#'    
                ,'prefix'       => 'A'    
                ,'starting'     => 1000
            ]
           ,'tablenames' => [
                 'bm_ints'              => 'bolt_ints'   
                ,'bm_calendar'          => 'bolt_bm_calendar'    
                ,'bm_calendar_weeks'    => 'bolt_bm_calendar_weeks'      
                ,'bm_calendar_months'   => 'bolt_bm_calendar_months'  
                ,'bm_calendar_quarters' => 'bolt_bm_calendar_quarters'  
                ,'bm_calendar_years'    => 'bolt_bm_calendar_years'
                
                ,'bm_timeslot'          => 'bolt_bm_timeslot'
                ,'bm_timeslot_day'      => 'bolt_bm_timeslot_day'
                ,'bm_timeslot_year'     => 'bolt_bm_timeslot_year'
                
                ,'bm_schedule_membership' => 'bolt_bm_schedule_membership'
                ,'bm_schedule_team'       => 'bolt_bm_schedule_team'
                ,'bm_schedule'            => 'bolt_bm_schedule'
                ,'bm_schedule_slot'       => 'bolt_bm_schedule_slot'
                ,'bm_schedule_team_members' => 'bolt_bm_schedule_team_members'
                
                
                ,'bm_booking'             => 'bolt_bm_booking' 
                ,'bm_booking_conflict'    => 'bolt_bm_booking_conflict'
                
                ,'bm_rule_type'           => 'bolt_bm_rule_type'
                ,'bm_rule'                => 'bolt_bm_rule'
                ,'bm_rule_series'         => 'bolt_bm_rule_series'
                ,'bm_rule_schedule'       => 'bolt_bm_rule_schedule'
                
                ,'bm_tmp_rule_series'     => 'bm_tmp_rule_series'
                
                ,'bm_customer'            => 'bolt_bm_customer'
                ,'bm_activity'            => 'bolt_bm_activity'
                ,'bm_appointment_status'  => 'bolt_bm_appointment_status'
                ,'bm_appointment'         => 'bolt_bm_appointment'
                
            ],
            'leadtime' => 'P1D'
       
       ];
        
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
      
         
        $aConfig = $this->getAppConfig();
     
        $this->oTestAPI = new BookMeService($app, $aConfig);
        
        return $this->app = $app;
    }
    
    protected function setUp()
    {
        # Truncate the Tables
        $aConfig = $this->getAppConfig();
       
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (1,'workday',true,false,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (2,'break',false,true,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (3,'holiday',false,true,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (4,'overtime',false,false,true)");

        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('W','Waiting')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('A','Assigned')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('D','Completed')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('C','Canceled')");

       
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
    
}
/* End of Unit Test */

