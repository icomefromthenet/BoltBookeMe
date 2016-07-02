<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\SetupException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\RolloverTimeslotCommand;


class SlotRolloverTest extends ExtensionTest
{
    
    
    
   protected function handleEventPostFixtureRun()
   {
      
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      
      $oStartYear       = clone $oNow;
      $oStartYear->setDate($oNow->format('Y'),1,1);
      
      $oService->addCalenderYears(2,$oStartYear);
      
      
      // Create Timeslots for first year as out test is see if the rollover will add them remainder
      
      $iFiveMinuteTimeslot    = $oService->addTimeslot(5,$oNow->format('Y'));
      $iTenMinuteTimeslot     = $oService->addTimeslot(10,$oNow->format('Y'));
      $iSevenMinuteTimeslot    = $oService->addTimeslot(7,$oNow->format('Y'));
      
      $this->aDatabaseId = [
        'five_minute'            => $iFiveMinuteTimeslot,
        'ten_minute'             => $iTenMinuteTimeslot,
        'fifteen_minute'         => $iFifteenMinuteTimeslot,
      ];
      
      
   }  
   
    
    
   
    /**
    * @group Setup
    */ 
    public function testSlotRollover()
    {
       $oStartYear = \DateTime::createFromFormat('Y-m-d','2015-01-01');
       
       // Test disabled toggle
       $this->RolloverTimeSlotTest($this->aDatabaseId['five_minute']);
     
      
    }
    
  
 

    
    
    protected function RolloverTimeSlotTest($iSlotId)
    {
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
                                                                            
        $iDaysInYear  = date("z", mktime(0,0,0,12,31,$oNow->format('Y'))) + 1;
        $iDaysInYear += date("z", mktime(0,0,0,12,31,($oNow->format('Y')+1))) + 1;
        
        $this->assertGreaterThanOrEqual($iDayCount *$iDaysInYear, $iYearCount,'The 2 year slot count is less than expected' );
      
       
    }
    

   
    
    
}
/* End of class */
