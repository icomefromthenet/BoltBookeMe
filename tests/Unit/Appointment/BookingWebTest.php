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

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Handler\TakeBookingHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Decorator\MaxBookingsDecorator;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Decorator\LeadTimeDecorator;
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
      
      
      // Make sure all slots are available
      $oDatabase->executeUpdate(
        'UPDATE `bolt_bm_schedule_slot` 
         SET `is_available`=1,`is_excluded`=0,`is_override`=0,`is_closed`=0 
         WHERE  `booking_id` is null'  
      );
      
      return;
    
   }  
   
   /**
    * @group Booking
    */ 
   public function testBookingSteps()
   {
      $oNow       = $this->getNow();
      
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),3,15);
      $oOpen->setTime(10,20,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),3,15);
      $oClose->setTime(10,40,0);
      
      $this->SucessfulyTakeBooking($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose,4);
    
    
      // Take a second booking so we can test if max check works
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),3,15);
      $oOpen->setTime(11,00,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),3,15);
      $oClose->setTime(11,30,0);
      
      $this->FailedMaxBookingCheck($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
   
      // Take a second booking so we can test if max check works Whe no violation
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),3,16);
      $oOpen->setTime(11,00,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),3,16);
      $oClose->setTime(11,30,0);
      
      $this->PassMaxBookingCheck($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
   
      
      // Want to try do a booking for tomorrow but we requre a booking to be made ahead of time
      // by 2 day
      $oOpen  =  clone $oNow;
      $oOpen->modify('+1 Day');
      $oOpen->setTime(10,45,0);
      
      $oClose = clone $oNow;
      $oClose->modify('+1 Day');
      $oClose->setTime(11,00,0);
      
      $this->FailedLeadTimeBookingCheck($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
    
      $oOpen  =  clone $oNow;
      $oOpen->modify('+4 Day');
      $oOpen->setTime(10,45,0);
      
      $oClose = clone $oNow;
      $oClose->modify('+4 Day');
      $oClose->setTime(11,00,0);
      
      $this->PassedLeadTimeBookingCheck($this->aDatabaseId['schedule_member_one'],$oOpen,$oClose);
    
   
   }
   
   
   
   public function SucessfulyTakeBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot, $iExpectedSlotCount)
   {
        
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oNow        = $this->getNow();
        $aTableNames = $this->getTableNames();
        
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P1D'));
        
        $oHandler  = new TakeBookingHandler($aTableNames,$oDatabase); 
        
        $oHandler->handle($oCommand);
        
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
   
   
   
   
   public function PassMaxBookingCheck($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot) 
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P1D'));
        
        $oMockApptHandler = $this->getMockBuilder('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Tests\\Mock\\MockHandler')
                                 ->setMethods(['handle'])
                                 ->getMock();
                                 
        $oMockApptHandler->expects($this->once())
                         ->method('handle')
                         ->with($this->equalTo($oCommand));
        
        $oHandler = new MaxBookingsDecorator($oMockApptHandler, $aTableNames, $oDatabase); 
       
            
        $oHandler->handle($oCommand);
        
   }
   
   public function FailedMaxBookingCheck($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P1D'));
        
        $oMockApptHandler = $this->getMockBuilder('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Tests\\Mock\\MockHandler')
                                 ->setMethods(['handle'])
                                 ->getMock();
                                 
            
        $oHandler = new MaxBookingsDecorator($oMockApptHandler, $aTableNames, $oDatabase); 
       
        try {
            
            $oHandler->handle($oCommand);
        
            $this->assertFalse(true,'The Max Booking check should of failed');
            
        }
        catch(BookingException $e) {
           $this->assertEquals('Max bookings taken for calendar day for schedule at id 1 time from '.$oOpeningSlot->format('Y-m-d H:i:s').' until '.$oClosingSlot->format('Y-m-d H:i:s'), $e->getMessage());
        }
    
   }
   
   
   public function FailedLeadTimeBookingCheck($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames(); 
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P2D'));
      
         $oMockApptHandler = $this->getMockBuilder('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Tests\\Mock\\MockHandler')
                                 ->setMethods(['handle'])
                                 ->getMock();
                                 
            
        $oHandler = new LeadTimeDecorator($oMockApptHandler, $aTableNames, $oDatabase); 
       
       
        try {
        
            $oHandler->handle($oCommand);
           
            $this->assertFalse(true,'The Lead Time Booking check should of failed');
            
        }
        catch(BookingException $e) {
           $this->assertEquals('Unable to create booking it has been taken within the required lead time',$e->getMessage());
        }
    
   }
   
   public function PassedLeadTimeBookingCheck($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
   {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames(); 
        $oNow        = $this->getNow();
       
        $oCommand  = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, 1, new DateInterval('P2D'));
      
        $oMockApptHandler = $this->getMockBuilder('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Tests\\Mock\\MockHandler')
                                 ->setMethods(['handle'])
                                 ->getMock();
        
                                      
        $oMockApptHandler->expects($this->once())
                         ->method('handle')
                         ->with($this->equalTo($oCommand));
                            
            
        $oHandler = new LeadTimeDecorator($oMockApptHandler, $aTableNames, $oDatabase); 
       
       

        $oHandler->handle($oCommand);

   }
  
}
/* end of file */
