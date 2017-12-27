<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Appointment;

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
      $oService = $this->getTestAPI();
      $oNow     = $this->getNow();
      
      $oStartYear = clone $oNow;
      $oStartYear->setDate($oNow->format('Y'),1,1);
      
      $oService->addCalenderYears(1,$oStartYear);
      
      // Create Timeslots
      
      $iFiveMinuteTimeslot    = $oService->addTimeslot(5,$oNow->format('Y'));
      $iTenMinuteTimeslot     = $oService->addTimeslot(10,$oNow->format('Y'));
      $iSevenMinuteTimeslot    = $oService->addTimeslot(7,$oNow->format('Y'));

      $oService->toggleSlotAvability($iTenMinuteTimeslot);    
  
      // Register new Members
  
      $iMemberOne   = $oService->registerMembership('Bob Builder');
      $iMemberTwo   = $oService->registerMembership('Bobs Assisstant');
      $iMemberThree = $oService->registerMembership('Bill Builder');
      $iMemberFour  = $oService->registerMembership('Bill Assistant');
    
      // Register new Teams    
    
      $iTeamOne     = $oService->registerTeam('Bob Team');
      $iTeamTwo     = $oService->registerTeam('Bill Team');
      
      
       // Schedules
      
      $iMemberOneSchedule   = $oService->startSchedule($iMemberOne,   $iFiveMinuteTimeslot, $oNow->format('Y'));
      $iMemberTwoSchedule   = $oService->startSchedule($iMemberTwo,   $iFiveMinuteTimeslot, $oNow->format('Y'));
      $iMemberThreeSchedule = $oService->startSchedule($iMemberThree, $iFiveMinuteTimeslot, $oNow->format('Y'));
      $iMemberFourSchedule  = $oService->startSchedule($iMemberFour,  $iFiveMinuteTimeslot, $oNow->format('Y'));
      
      // Stop a schedule
      
      $oService->stopSchedule($iMemberFourSchedule,$oNow->setDate($oNow->format('Y'),6,1));
      
      // Assign members to team one as their using $iFiveMinuteTimeslot
      
      $oService->assignTeamMember($iMemberOne,$iTeamOne,$iMemberOneSchedule);
      $oService->assignTeamMember($iMemberTwo,$iTeamOne,$iMemberTwoSchedule);
     
      $oService->assignTeamMember($iMemberThree,$iTeamOne,$iMemberThreeSchedule);
      $oService->assignTeamMember($iMemberFour,$iTeamOne,$iMemberFourSchedule);
      
      // Create some Rules 
      
      $oSingleDate = clone $oNow;
      $oSingleDate->setDate($oNow->format('Y'),1,14);
        
      $oDayWorkDayRuleStart = clone $oNow;
      $oDayWorkDayRuleStart->setDate($oNow->format('Y'),1,1);
      
      $oDayWorkDayRuleEnd = clone $oNow;
      $oDayWorkDayRuleEnd->setDate($oNow->format('Y'),12,31);
      
      $oHolidayStart = clone $oNow;
      $oHolidayStart->setDate($oNow->format('Y'),8,7);
      
      $oHolidayEnd   = clone $oNow; 
      $oHolidayEnd->setDate($oNow->format('Y'),8,14);
      
      
      $iNineAmSlot = (12*9) *5;
      $iFivePmSlot = (12*17)*5;
      $iTenPmSlot  = (12*20)*5;    
        
      $iRepeatWorkDayRule    = $oService->createRepeatingWorkDayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'1-5','*','2-12','*', 'Repeat Work Day Rule');
      $iSingleWorkDayRule    = $oService->createSingleWorkDayRule($oSingleDate,$iFiveMinuteTimeslot,$iFivePmSlot,$iTenPmSlot, 'Single Work Day Rule'); 
      
      $iMidaySlot = (12*12)*5;
      $iOnePmSlot = (12*13)*5;
      
      $iEightPmSlot  = (12*18)*5;
      $iEightThirtyPmSlot = ((12*18) + 6)*5;
      
      $iRepeatBreakRule      = $oService->createRepeatingBreakRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iMidaySlot,$iOnePmSlot,'1-5','*','2-12','*', 'Repat Break Rule');
      $iSingleBreakRule      = $oService->createSingleBreakRule($oSingleDate,$iFiveMinuteTimeslot,$iEightPmSlot,$iEightThirtyPmSlot, 'Single Break Rule'); 
            
            
      $iRepeatHolidayRule    = $oService->createRepeatingHolidayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*','*', 'Repeat Holiday Rule');    
      $iSingleHolidayRule      = $oService->createSingleHolidayRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot, 'Single Holiday Rule');             
    
    
      $iRepeatOvertimeRule   = $oService->createRepeatingOvertimeRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*','*', 'Repeat Overtime Rule');
      $iSingleOvertimeRule   = $oService->createSingleOvertmeRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot, 'Single Overtime Rule');
      
      
      // Link Rules to Schedule
      $oService->assignRuleToSchedule($iRepeatWorkDayRule,$iMemberOneSchedule,true);
      $oService->assignRuleToSchedule($iSingleWorkDayRule,$iMemberOneSchedule,false);
      $oService->assignRuleToSchedule($iRepeatBreakRule,$iMemberOneSchedule,true);
      $oService->assignRuleToSchedule($iSingleBreakRule,$iMemberOneSchedule,false);
      $oService->assignRuleToSchedule($iRepeatHolidayRule,$iMemberOneSchedule,true);
      $oService->assignRuleToSchedule($iSingleHolidayRule,$iMemberOneSchedule,false);
      $oService->assignRuleToSchedule($iRepeatOvertimeRule,$iMemberOneSchedule,true);
      $oService->assignRuleToSchedule($iSingleOvertimeRule,$iMemberOneSchedule,false);
    
      $oService->assignRuleToSchedule($iRepeatWorkDayRule,$iMemberTwoSchedule,true);
      $oService->assignRuleToSchedule($iSingleWorkDayRule,$iMemberTwoSchedule,false);
      $oService->assignRuleToSchedule($iRepeatBreakRule,$iMemberTwoSchedule,true);
      $oService->assignRuleToSchedule($iSingleBreakRule,$iMemberTwoSchedule,false);
      $oService->assignRuleToSchedule($iRepeatHolidayRule,$iMemberTwoSchedule,true);
      $oService->assignRuleToSchedule($iSingleHolidayRule,$iMemberTwoSchedule,false);
      $oService->assignRuleToSchedule($iRepeatOvertimeRule,$iMemberTwoSchedule,true);
      $oService->assignRuleToSchedule($iSingleOvertimeRule,$iMemberTwoSchedule,false);
      
      $oService->assignRuleToSchedule($iRepeatWorkDayRule,$iMemberThreeSchedule,true);
      $oService->assignRuleToSchedule($iSingleWorkDayRule,$iMemberThreeSchedule,false);
      $oService->assignRuleToSchedule($iRepeatBreakRule,$iMemberThreeSchedule,true);
      $oService->assignRuleToSchedule($iSingleBreakRule,$iMemberThreeSchedule,false);
      $oService->assignRuleToSchedule($iRepeatHolidayRule,$iMemberThreeSchedule,true);
      $oService->assignRuleToSchedule($iSingleHolidayRule,$iMemberThreeSchedule,false);
      $oService->assignRuleToSchedule($iRepeatOvertimeRule,$iMemberThreeSchedule,true);
      $oService->assignRuleToSchedule($iSingleOvertimeRule,$iMemberThreeSchedule,false);
      
      $oService->assignRuleToSchedule($iRepeatWorkDayRule,$iMemberFourSchedule,true);
      $oService->assignRuleToSchedule($iSingleWorkDayRule,$iMemberFourSchedule,false);
      $oService->assignRuleToSchedule($iRepeatBreakRule,$iMemberFourSchedule,true);
      $oService->assignRuleToSchedule($iSingleBreakRule,$iMemberFourSchedule,false);
      $oService->assignRuleToSchedule($iRepeatHolidayRule,$iMemberFourSchedule,true);
      $oService->assignRuleToSchedule($iSingleHolidayRule,$iMemberFourSchedule,false);
      $oService->assignRuleToSchedule($iRepeatOvertimeRule,$iMemberFourSchedule,true);
      $oService->assignRuleToSchedule($iSingleOvertimeRule,$iMemberFourSchedule,false);
      
      //  Refresh the Members Schedules
      
      $oService->resfreshSchedule($iMemberOneSchedule);
      $oService->resfreshSchedule($iMemberTwoSchedule);
      $oService->resfreshSchedule($iMemberThreeSchedule);
      $oService->resfreshSchedule($iMemberFourSchedule);
    
    
     // Take some manual bookings
       
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(17,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(17,20,0);
    
      $iBookingMemberOneFirst = $oService->takeManualBooking($iMemberOneSchedule,$oOpen,$oClose);
     
      // save identifiers for use below    
            
      $this->aDatabaseId = [
        'five_minute'            => $iFiveMinuteTimeslot,
        'ten_minute'             => $iTenMinuteTimeslot,
        'fifteen_minute'         => $iFifteenMinuteTimeslot,
        'member_one'             => $iMemberOne,
        'member_two'             => $iMemberTwo,
        'member_three'           => $iMemberThree,
        'member_four'            => $iMemberFour,
        'team_two'               => $iTeamTwo,
        'team_one'               => $iTeamOne,
        'work_repeat'            => $iRepeatWorkDayRule,
        'work_single'            => $iSingleWorkDayRule,
        'break_repeat'           => $iRepeatBreakRule,
        'break_single'           => $iSingleBreakRule,
        'holiday_repeat'         => $iRepeatHolidayRule,
        'holiday_single'         => $iSingleHolidayRule,
        'overtime_repeat'        => $iRepeatOvertimeRule,
        'overtime_single'        => $iSingleOvertimeRule,
        'schedule_member_one'    => $iMemberOneSchedule,
        'schedule_member_two'    => $iMemberTwoSchedule,
        'schedule_member_three'  => $iMemberThreeSchedule,
        'schedule_member_four'   => $iMemberFourSchedule,
        'booking_member_one_first' => $iBookingMemberOneFirst,
      ];
      
    
      
      
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
