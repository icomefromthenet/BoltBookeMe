<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Bolt\Tests\BoltUnitTest;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\BookMeService;


/**
 * Ensure that the ExtensionName extension loads correctly.
 *
 */
class ExtensionTest extends BoltUnitTest
{
    
    /**
     * @var BookMeService
     */ 
    protected $oTestAPI;
    
    
    protected function handleEventPostFixtureRun()
   {
      return false;
   }  
    
    
    protected function makeApp()
    {
        $bolt = parent::makeApp();
        
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
        
        
        
        return $bolt;
    }
    
    protected function getAppConfig()
    {
        return [
           'tablenames' => [
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
                
            ]
       
       ];
        
    }
    
    protected function getApp($boot = true)
    {
        if ($this->app) {
            return $this->app;
        }
        
       
        $app = parent::getApp(false);
        
         
       $aConfig = $this->getAppConfig();
     
       
       
        $this->oTestAPI = new BookMeService($app, $aConfig);
        
        return $this->app = $app;
    }
    
    protected function setUp()
    {
        # Truncate the Tables
        $aConfig = $this->getAppConfig();
        
        $this->getDatabaseAdapter()->exec('SET foreign_key_checks = 0');
        foreach($aConfig['tablenames'] as $sTable) {
            $this->getDatabaseAdapter()->exec('truncate table '.$sTable);
        }
        
       $this->getDatabaseAdapter()->exec('SET foreign_key_checks = 1');
       
       
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

