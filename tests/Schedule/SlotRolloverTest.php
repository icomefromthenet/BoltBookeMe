<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Schedule;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\RolloverTimeslotCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\SetupException;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\CalendarSeed;

class SlotRolloverTest extends ExtensionTest
{
    
    
    
   protected function handleEventPostFixtureRun()
   {
      
       $oNow        = $this->getNow();
       
       $oStartYear       = clone $oNow;
       $oStartYear->setDate($oNow->format('Y'),1,1);
      
       $iStartYear = $oStartYear->format('Y') + 3;
       $iEndYear   =$oStartYear->format('Y') + 4;
      
       $aConfig = $this->getAppConfig();
      
       $oCalendarSeed = new CalendarSeed($this->getDatabaseAdapter(),$aConfig['tablenames'],$iStartYear, $iEndYear);
      
       $oCalendarSeed->executeSeed();
   }  
   
    
    
   
    /**
    * @group schedule
    */ 
    public function testSlotRollover()
    {
       // Test disabled toggle
       $iSlotId = $this->aDatabaseId['five_minute'];
       
        $oContainer  = $this->getContainer();
        
        $oCommandBus = $this->getCommandBus(); 
        
        $oNow        = $this->getNow();
       
        $oCommand  = new RolloverTimeslotCommand($iSlotId);
       
        $oCommandBus->handle($oCommand);
        
        $this->assertNotEmpty($oCommand->getRollOverNumber());
        
        $numberSlots = (int)((60*24) / 5);
        
        $iDayCount = (int) $this->getDatabaseAdapter()->fetchColumn("select count(open_minute) 
                                                                           from bolt_bm_timeslot_day 
                                                                           where timeslot_id = ? "
                                                                           ,[$oCommand->getTimeSlotId()],0,[]);
       
       
        $this->assertEquals($numberSlots,$iDayCount,'The Day slot are less than expected number'); 
        
        $iYearCount = (int) $this->getDatabaseAdapter()->fetchColumn("select count(open_minute) 
                                                                            from bolt_bm_timeslot_year 
                                                                            where timeslot_id = ? "
                                                                            ,[$oCommand->getTimeSlotId()],0,[]);
                                                                            
        $iDaysInYear  = date("z", mktime(0,0,0,12,31,$oNow->format('Y'))) + 3;
        $iDaysInYear += date("z", mktime(0,0,0,12,31,($oNow->format('Y')+1))) + 4;
        
        $this->assertGreaterThanOrEqual($iDayCount *$iDaysInYear, $iYearCount,'The 2 year slot count is less than expected' );
      
      
    }
    

    
    
}
/* End of class */
