<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Appointment;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CancelApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\AssignApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CompleteApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\MoveApptWaitingCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\ApptEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\AppointmentException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;




class AppointmentAdvTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
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
      $oDayWorkDayRuleStart->setDate($oNow->format('Y'),2,1);
      
      $oDayWorkDayRuleEnd = clone $oNow;
      $oDayWorkDayRuleEnd->setDate($oNow->format('Y'),12,31);
      
      $oHolidayStart = clone $oNow;
      $oHolidayStart->setDate($oNow->format('Y'),8,7);
      
      $oHolidayEnd   = clone $oNow; 
      $oHolidayEnd->setDate($oNow->format('Y'),8,14);
      
      
      $iNineAmSlot = (12*9) *5;
      $iFivePmSlot = (12*17)*5;
      $iTenPmSlot  = (12*20)*5;    
        
      $iRepeatWorkDayRule    = $oService->createRepeatingWorkDayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','*','2-12','*','Repeat Work Day Rule');
      $iSingleWorkDayRule    = $oService->createSingleWorkDayRule($oSingleDate,$iFiveMinuteTimeslot,$iFivePmSlot,$iTenPmSlot,'Single Work Day Rule'); 
      
      $iMidaySlot = (12*12)*5;
      $iOnePmSlot = (12*13)*5;
      
      $iEightPmSlot  = (12*18)*5;
      $iEightThirtyPmSlot = ((12*18) + 6)*5;
      
      $iRepeatBreakRule      = $oService->createRepeatingBreakRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iMidaySlot,$iOnePmSlot,'1-5','*','2-12','*','Repear Break Rule');
      $iSingleBreakRule      = $oService->createSingleBreakRule($oSingleDate,$iFiveMinuteTimeslot,$iEightPmSlot,$iEightThirtyPmSlot,'Single Break Rule'); 
            
            
      $iRepeatHolidayRule    = $oService->createRepeatingHolidayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*','*','Repeat Holiday Rule');    
      $iSingleHolidayRule      = $oService->createSingleHolidayRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'Single Holiday Rule');             
    
    
      $iRepeatOvertimeRule   = $oService->createRepeatingOvertimeRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*','*','Repeat Overtime Rule');
      $iSingleOvertimeRule   = $oService->createSingleOvertmeRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'Single Overtime Rule');
      
      
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
    
      
      
      // Create some manual bookings
      
        
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),1,14);
      $oOpen->setTime(17,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),1,14);
      $oClose->setTime(17,20,0);
    
      $iBookingMemberOneFirst = $oService->takeManualBooking($iMemberOneSchedule,$oOpen,$oClose);
   
      
      $oOpen  =  clone $oNow;
      $oOpen->setDate($oNow->format('Y'),2,13);
      $oOpen->setTime(13,0,0);
      
      $oClose = clone $oNow;
      $oClose->setDate($oNow->format('Y'),2,13);
      $oClose->setTime(13,20,0);
    
      $iBookingMemberOneSecond = $oService->takeManualBooking($iMemberOneSchedule,$oOpen,$oClose);
      
      // Create some customers
      
      $iCustomerOneId     = $oService->createCustomer('Bob', 'Builder', 'bob@builder.com', '0404555555', '98172762', 'Bob Address Line One', 'Bob Address Line Two', 'Company One');
      $iCustomerTwoId     = $oService->createCustomer('Steve', 'Builder', 'seteve@builder.com', '0404555556', '98172762' , 'Steve Address Line One', 'Steve Address Line Two', 'Company two');
      $iCustomerThreeId   = $oService->createCustomer('Karen', 'Builder', 'karen@builder.com', '0404555557', '98172762' , 'Karen Address Line One', 'Karen Address Line Two', 'Company three');
   
    
      // Create Some Appointments
   
      $iApptCustomerOneId        = $oService->createAppointment($iCustomerOneId,'First Job Instruction');
      $iApptCustomerTwoId        = $oService->createAppointment($iCustomerTwoId,'Second Job Instruction');
      $iApptCustomerOne2ndApptId = $oService->createAppointment($iCustomerOneId,'Third Job Instruction');
      
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
        'customer_1'             => $iCustomerOneId,
        'customer_2'             => $iCustomerTwoId,
        'customer_3'             => $iCustomerThreeId,
        'booking_member_one_1'   => $iBookingMemberOneFirst,
        'booking_member_one_2'   => $iBookingMemberOneSecond,
        'appt_customer_one_1'      => $iApptCustomerOneId,
        'appt_customer_one_2'      => $iApptCustomerOne2ndApptId,
        'appt_customer_two_1'      => $iApptCustomerTwoId,
        
      ];
      
      return;
   }  
   
   
    /**
    * @group Setup
    */ 
    public function testAppointmentCommands()
    {
       
       // This appt should be on the waiting list
       $iApptCustomerOneId      = $this->aDatabaseId['appt_customer_one_1'];
       $iApptCustomerOne2ndApptId = $this->aDatabaseId['appt_customer_one_2'];
       $iApptCustomerTwoId      = $this->aDatabaseId['appt_customer_two_1'];
       
       $iBookingMemberOneId     = $this->aDatabaseId['booking_member_one_1'];
       $iBookingMemberOne2ndId     = $this->aDatabaseId['booking_member_one_2'];
       
       // test cancel
       $this->CancelAppointmentTest($iApptCustomerOneId);    
       
       
       // test assign
       $this->AssignAppointmentTest($iApptCustomerTwoId,$iBookingMemberOneId);
       
       // test assign failes in wrong status
       $this->AssignApptFailsWrongStatusTest($iApptCustomerTwoId,$iBookingMemberOneId);
     
       // test complete appointment
       $this->CompleteAppointmentTest($iApptCustomerTwoId);
       
       $this->AssignAppointmentTest($iApptCustomerOne2ndApptId,$iBookingMemberOne2ndId);
       $this->MoveWaitingListTest($iApptCustomerOne2ndApptId);
       $this->MoveCanceledWaitingListTest($iApptCustomerOneId);
       
    }
    
   
    
    protected function CancelAppointmentTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new CancelApptCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('C',$aResult['status_code']);
      
        
        return $iApptId;
        
    }
    
    protected function AssignAppointmentTest($iApptId, $iBookingId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $sInstructions = 'instructions are here';
            
            $oCommand  = new AssignApptCommand($iApptId,$iBookingId,$sInstructions);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('A',$aResult['status_code']);
        $this->assertEquals($iBookingId,$aResult['booking_id']);
        $this->assertEquals($sInstructions,$aResult['instructions']);
      
        
        return $iApptId;
        
    }
    
    protected function AssignApptFailsWrongStatusTest($iApptId, $iBookingId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $sInstructions = 'instructions are here';
            
            $oCommand  = new AssignApptCommand($iApptId,$iBookingId,$sInstructions);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
        catch(AppointmentException $e) {
           
           $this->assertEquals($e->getMessage(),'Unable to assign booking at id '.$oCommand->getBookingId().' to appointment at id '.$oCommand->getAppointmentId(). ' the appointment is either not found or not in correct status ');
            return;   
        }
        
        $this->assertFalse(true,'Should of failed appointment assignment');
        
    }
    
    protected function CompleteAppointmentTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new CompleteApptCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('D',$aResult['status_code']);
        
        
      
        
        return $iApptId;
        
    }
    
    protected function MoveWaitingListTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new MoveApptWaitingCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('W',$aResult['status_code']);
        $this->assertEmpty($aResult['booking_id']);
        
      
        
        return $iApptId;
        
    }
    
     protected function MoveCanceledWaitingListTest($iApptId)
    {
        $oContainer  = $this->getContainer();
        $oCommandBus = $this->getCommandBus(); 
       
        try {
            
            $oCommand  = new MoveApptWaitingCommand($iApptId);
           
            
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iApptId = $oCommand->getAppointmentId();
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_appointment 
                                            where appointment_id = ? ",[$iApptId],[Type::getType(Type::INTEGER)]);
       
       
        $this->assertEquals($iApptId,$aResult['appointment_id'],'Appt id do not equal');
        $this->assertEquals('W',$aResult['status_code']);
        $this->assertEmpty($aResult['booking_id']);
        
      
        
        return $iApptId;
        
    }
    
}
/* end of file */
