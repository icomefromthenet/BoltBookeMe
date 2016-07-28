<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CancelApptCommand;

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
        
      $iRepeatWorkDayRule    = $oService->createRepeatingWorkDayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'1-5','*','2-12');
      $iSingleWorkDayRule    = $oService->createSingleWorkDayRule($oSingleDate,$iFiveMinuteTimeslot,$iFivePmSlot,$iTenPmSlot); 
      
      $iMidaySlot = (12*12)*5;
      $iOnePmSlot = (12*13)*5;
      
      $iEightPmSlot  = (12*18)*5;
      $iEightThirtyPmSlot = ((12*18) + 6)*5;
      
      $iRepeatBreakRule      = $oService->createRepeatingBreakRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iMidaySlot,$iOnePmSlot,'1-5','*','2-12');
      $iSingleBreakRule      = $oService->createSingleBreakRule($oSingleDate,$iFiveMinuteTimeslot,$iEightPmSlot,$iEightThirtyPmSlot); 
            
            
      $iRepeatHolidayRule    = $oService->createRepeatingHolidayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*');    
      $iSingleHolidayRule      = $oService->createSingleHolidayRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot);             
    
    
      $iRepeatOvertimeRule   = $oService->createRepeatingOvertimeRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*');
      $iSingleOvertimeRule   = $oService->createSingleOvertmeRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot);
      
      
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
    
      
      // Create some bookings
      
      //$oService->take
      
      
      // Create some customers
      
      $iCustomerOneId     = $oService->createCustomer('Bob', 'Builder', 'bob@builder.com', '0404555555', '98172762', 'Bob Address Line One', 'Bob Address Line Two', 'Company One');
      $iCustomerTwoId     = $oService->createCustomer('Steve', 'Builder', 'seteve@builder.com', '0404555556', '98172762' , 'Steve Address Line One', 'Steve Address Line Two', 'Company two');
      $iCustomerThreeId   = $oService->createCustomer('Karen', 'Builder', 'karen@builder.com', '0404555557', '98172762' , 'Karen Address Line One', 'Karen Address Line Two', 'Company three');
   
      
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
        
      ];
      
      return;
   }  
   
   
    /**
    * @group Setup
    */ 
    public function testAppointmentCommands()
    {
        
       
       // test cancel
       $this->CancelAppointmentTest($iApptCustomerOneId);    
       
       // test assign
       
       
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
}
/* end of file */
