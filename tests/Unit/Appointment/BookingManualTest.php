<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Appointment;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\ManualBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\LookBookingConflictsCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\ClearBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\BookingException;

class BookingManualTest extends ExtensionTest
{
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      $oService = $this->getTestAPI();
      $oNow     = $this->getNow();
      
      return;
      
   }  
   
   /**
    * @group Booking
    */ 
   public function testBookingSteps()
   {
      $oNow       = $this->getNow();
      
      // Test a sucessful booking (No oveertime slots)
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(17,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(17,20,0);
      
      $iSucessfulBooking = $this->SucessfulyTakeBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose,4);
      
      // Check for duplicate failure
      
      $this->FailOnDuplicateBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
      
    
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,28);
      $oOpen->setTime(9,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,28);
      $oClose->setTime(9,45,0);
      
      $this->SucessfulyTakeOvertimeBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose,9);
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(18,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(18,45,0);
      
      $this->FailBreakBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
      
      // Test Conflict Checker
      
      $this->ConfictCheckerTest($this->aDatabaseId['schedule_member_one'],$iSucessfulBooking,$oNow);
      
      // Clear a booking
      $this->BookingClearTest($iSucessfulBooking);
   }
   
   
   
   public function SucessfulyTakeBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot, $iExpectedSlotCount)
   {
        
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new ManualBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot);
        
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
        
        return $oCommand->getBookingId();
   }
   
   
   
   public function FailOnDuplicateBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus();  
       
        $oCommand  = new ManualBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot);
        
        try {
        
            $oCommandBus->handle($oCommand);
            $this->assertFalse(true,'A Duplicate Booking was allowed');
            
        }
        catch(BookingException $e) {
           
           $this->assertEquals($e->getMessage(),'Unable to reserve schedule slots for schedule at id 1 time from '.$oOpeningSlot->format('Y-m-d H:i:s').' until '.$oClosingSlot->format('Y-m-d H:i:s'));
           
        }
    
   }
  
  
   public function SucessfulyTakeOvertimeBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot, $iExpectedSlotCount)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new ManualBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot);
        
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
  
  
   public function FailBreakBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new ManualBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot);
        
        try {
        
            $oCommandBus->handle($oCommand);
            $this->assertFalse(true,'A booking on break was allowed');
            
        }
        catch(BookingException $e) {
           
           $this->assertEquals($e->getMessage(),'Unable to reserve schedule slots for schedule at id 1 time from '.$oOpeningSlot->format('Y-m-d H:i:s').' until '.$oClosingSlot->format('Y-m-d H:i:s'));
           
        }
    
   }
   
   
 
   public function ConfictCheckerTest($iScheduleId, $iSucessfulBooking, $oNow)
   {
       $oContainer  = $this->getContainer();
       $oDatabase   = $this->getDatabaseAdapter();    
       $oCommandBus = $this->getCommandBus();  
       $iCalYear = $this->getNow()->format('Y');
       
       // Conflict 1 Booking Exclusion Rule now exists or override removed
       $sSql  ="";
       $sSql .=" UPDATE bolt_bm_schedule_slot SET is_override = false, is_available = true, is_excluded = true, booking_id = ?, is_closed = false " ;
       $sSql .=" WHERE schedule_id = ?  AND slot_open >= '".$iCalYear."-08-01 12:00:00' AND slot_close <= '".$iCalYear."-08-01 12:45:00'";
       
       $oDatabase->executeUpdate($sSql,[$iSucessfulBooking,$iScheduleId],[Type::INTEGER,Type::INTEGER]);
       
       
       // Conflict 2 Booking Schedule has been closed
       $sSql  ="";
       $sSql .=" UPDATE bolt_bm_schedule_slot SET is_override = false, is_available = true, is_excluded = false, booking_id = ?, is_closed = true " ;
       $sSql .=" WHERE schedule_id = ?  AND slot_open >= '".$iCalYear."-08-01 15:00:00' AND slot_close <= '".$iCalYear."-08-01 15:45:00'";
       
       $oDatabase->executeUpdate($sSql,[$iSucessfulBooking,$iScheduleId],[Type::INTEGER,Type::INTEGER]);
       
       $oStartYear = new DateTime();
       $oStartYear->setDate($oNow->format('Y'),1,1);
       $oStartYear->setTime(0,0,0);
       
       $oCommand = new LookBookingConflictsCommand($oStartYear);
       
       $oCommandBus->handle($oCommand);
       
       $this->assertEquals(1,$oCommand->getNumberConflictsFound());
       
   }
  
   
   public function BookingClearTest($iBookingId)
   {
       $oContainer  = $this->getContainer();
       $oDatabase   = $this->getDatabaseAdapter();    
       $oCommandBus = $this->getCommandBus();  
      
       $oCommand = new ClearBookingCommand($iBookingId);
       
       $oCommandBus->handle($oCommand);
       
        $iBookCount = (integer) $oDatabase->fetchColumn('SELECT 1 
                                                FROM bolt_bm_booking
                                                WHERE booking_id = ?'
                                                ,[$iBookingId]
                                                ,0
                                                ,[Type::INTEGER]);
        
        $this->assertEquals(0,$iBookCount,'The booking was not removed');
        
         $iBookCount = (integer) $oDatabase->fetchColumn('SELECT 1 
                                                FROM bolt_bm_booking_conflict
                                                WHERE booking_id = ?'
                                                ,[$iBookingId]
                                                ,0
                                                ,[Type::INTEGER]);
        
        $this->assertEquals(0,$iBookCount,'The booking conflict was not removed');
       
   }
   
}
/* end of file */
