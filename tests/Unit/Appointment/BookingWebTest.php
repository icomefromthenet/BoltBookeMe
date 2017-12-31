<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Appointment;

use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\WebBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\LookBookingConflictsCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\ClearBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\BookingException;

class BookingWebTest extends ExtensionTest
{
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      $oService   = $this->getTestAPI();
      $oNow       = $this->getNow();
      $oDatabase  = $this->getDatabaseAdapter();
      
       $oDatabase->executeUpdate(
        'DELETE FROM bolt_bm_booking WHERE 1=1'  
      );
      
      return;
    
   }  
   
   /**
    * @group Booking
    */ 
   public function testBookingSteps()
   {
      $oNow       = $this->getNow();
      
      
      // Take a second booking so we can test if max check works
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(17,20,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(17,40,0);
      
      $this->SucessfulyTakeBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose,4);
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(17,40,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(18,00,0);
      
      $this->FailMaxBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
      
      $oOpen  =  clone $oNow;
      $oOpen->setTime(17,20,0);
      
      $oClose = clone $oNow;
      $oClose->setTime(17,40,0);
      
      $this->FailLeadTimeBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
    
   }
   
   
   
   public function SucessfulyTakeBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot, $iExpectedSlotCount)
   {
        
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus(); 
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P1D'));
        
        $oCommandBus->handle($oCommand);
        
        // check if we have a booking saved
        $this->assertGreaterThanOrEqual(1,$oCommand->getBookingId());
        
        // verify the slots were reserved
        $iSlotCount = 0;
        
        $iSlotCount = (integer) $oDatabase->fetchColumn('SELECT count(*) 
                                                FROM bolt_bm_schedule_slot
                                                WHERE schedule_id = ? 
                                                and slot_open >= ?
                                                and slot_close <= ?
                                                and booking_id = ?'
                                                ,[$iScheduleId,$oOpeningSlot,$oClosingSlot,$oCommand->getBookingId()]
                                                ,0
                                                ,[Type::INTEGER, Type::DATETIME,Type::DATETIME,Type::INTEGER]);
        
        $this->assertEquals($iSlotCount,$iExpectedSlotCount,'The slots have not been reserved');
        
        
   }
   
   
   
   public function FailMaxBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus(); 
       
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P1D'));
      
       
        try {
        
            $oCommandBus->handle($oCommand);
            $this->assertFalse(true,'The Max Booking check should of failed');
            
        }
        catch(BookingException $e) {
           $this->assertEquals('Max bookings taken for calendar day for schedule at id 1 time from '.$oOpeningSlot->format('Y-m-d H:i:s').' until '.$oClosingSlot->format('Y-m-d H:i:s'), $e->getMessage());
        }
    
   }
   
   
   public function FailLeadTimeBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus(); 
       
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P1D'));
      
       
        try {
        
            $oCommandBus->handle($oCommand);
            $this->assertFalse(true,'The Max Booking check should of failed');
            
        }
        catch(BookingException $e) {
           $this->assertEquals('Unable to create booking it has been taken within the required lead time',$e->getMessage());
        }
    
   }
  
}
/* end of file */
