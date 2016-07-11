<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use DateTime;
use Doctrine\DBAL\Types\Type;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\AssignRuleToScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RemoveRuleFromScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\AssignTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\WithdrawlTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleException;



class ScheduleAdvanceTest extends ExtensionTest
{
    
    
    protected $aDatabaseId = [];
    
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      $oContainer = $this->getContainer();
      $oService   = $this->getTestAPI();
      $oNow       = $this->getNow();
      
      
      $oStart = clone $oNow;
      $oStart->setDate($oNow->format('Y'),1,1);
      
      $oService->addCalenderYears(5,$oStart);
      
      // Timeslots
      
      $iFiveMinuteTimeslot    = $oService->addTimeslot(5,$oNow->format('Y'));
      $iTenMinuteTimeslot     = $oService->addTimeslot(10,$oNow->format('Y'));
      $iFifteenMinuteTimeslot = $oService->addTimeslot(15,$oNow->format('Y'));

      $oService->toggleSlotAvability($iTenMinuteTimeslot);    
  
     // Teams
  
      $iMemberOne   = $oService->registerMembership('Bob Builder');
      $iMemberTwo   = $oService->registerMembership('Bobs Assistant');
      $iMemberThree = $oService->registerMembership('Bill Builder');
      $iMemberFour  = $oService->registerMembership('bills Assisstant');
    
      $iTeamOne     = $oService->registerTeam('Bob Team');
      $iTeamTwo     = $oService->registerTeam('Bills Tean');
         
         
      // Schedules
      $iMemberOneSchedule   = $oService->startSchedule($iMemberOne,   $iFiveMinuteTimeslot, $oNow->format('Y'));
      $iMemberTwoSchedule   = $oService->startSchedule($iMemberTwo,   $iFiveMinuteTimeslot, $oNow->format('Y'));
      $iMemberThreeSchedule = $oService->startSchedule($iMemberThree, $iFiveMinuteTimeslot, $oNow->format('Y'));
      $iMemberFourSchedule  = $oService->startSchedule($iMemberFour,  $iFiveMinuteTimeslot, $oNow->format('Y'));
      
      
      // Rules Single
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
      $iSingleBreakRule      = $oService->createSingleHolidayRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot);             
    
    
      $iRepeatOvertimeRule   = $oService->createRepeatingOvertimeRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*');
      $iSingleOvertimeRule   = $oService->createSingleOvertmeRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot);
      
      
      // Link Rules to Schedule
      
            
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
        'holiday_single'         => $iSingleBreakRule,
        'overtime_repeat'        => $iRepeatOvertimeRule,
        'overtime_single'        => $iSingleOvertimeRule,
        'schedule_member_one'    => $iMemberOneSchedule,
        'schedule_member_two'    => $iMemberTwoSchedule,
        'schedule_member_three'  => $iMemberThreeSchedule,
        'schedule_member_four'   => $iMemberFourSchedule,
        
      ];
      
      
   }  
   
   
    /**
    * @group Management
    */ 
    public function testScheduleCommands()
    {
        $iRuleOneId        = $this->aDatabaseId['work_repeat'];
        $iRuleTwoId        = $this->aDatabaseId['holiday_repeat'];
        $iRuleThreeId      = $this->aDatabaseId['overtime_repeat']; 
        
        $iScheduleId       = $this->aDatabaseId['schedule_member_two'];
        
        $iMemberOneId      = $this->aDatabaseId['member_one'];
        $iTeamOneId        = $this->aDatabaseId['team_one'];
        $iTeamOneScheduleId= $this->aDatabaseId['schedule_member_one'];
        
        $this->ApplyRulesTest($iScheduleId, $iRuleOneId,$iRuleTwoId,$iRuleThreeId);
        //$this->RefreshScheduleTest($iScheduleId);
        //$this->RemoveFromScheduleTest($iScheduleId, $iRuleOneId);
        //$this->AssignToTeam($iMemberOneId,$iTeamOneId,$iTeamOneScheduleId);
        //$this->WithdrawlToTeam($iMemberOneId,$iTeamOneId,$iTeamOneScheduleId);
       
    }
    
    protected function ApplyRulesTest($iScheduleId, $iRuleOneId,$iRuleTwoId, $iRuleThreeId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
      
        $oCommand = new AssignRuleToScheduleCommand($iScheduleId, $iRuleOneId, true);
        
        $this->getCommandBus()->handle($oCommand);
        
        $bRuleExists = (bool) $oDatabase->fetchColumn('SELECT 1 
                                                FROM bolt_bm_rule_schedule 
                                                WHERE schedule_id = ? 
                                                AND rule_id = ? 
                                                AND is_rollover = true',[$iScheduleId,$iRuleOneId],0);
        
        $this->assertTrue($bRuleExists,'Rule has not been linked to schedule');
        
        
        
        $oCommand = new AssignRuleToScheduleCommand($iScheduleId, $iRuleTwoId, true);
        
        $this->getCommandBus()->handle($oCommand);
        
        $bRuleExists = (bool) $oDatabase->fetchColumn('SELECT 1 
                                                FROM bolt_bm_rule_schedule 
                                                WHERE schedule_id = ? 
                                                AND rule_id = ? 
                                                AND is_rollover = true',[$iScheduleId,$iRuleTwoId],0);
        
        $this->assertTrue($bRuleExists,'Rule has not been linked to schedule');
        
        
        
        
        $oCommand = new AssignRuleToScheduleCommand($iScheduleId, $iRuleThreeId, true);
        
        $this->getCommandBus()->handle($oCommand);
        
        $bRuleExists = (bool) $oDatabase->fetchColumn('SELECT 1 
                                                FROM bolt_bm_rule_schedule 
                                                WHERE schedule_id = ? 
                                                AND rule_id = ? 
                                                AND is_rollover = true',[$iScheduleId,$iRuleThreeId],0);
        
        $this->assertTrue($bRuleExists,'Rule has not been linked to schedule');
    }
    
    
    protected function RefreshScheduleTest($iScheduleId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        
        $oCommandBus = $oContainer->getCommandBus(); 
       
        $oCommand = new RefreshScheduleCommand($iScheduleId);
       
        $this->getCommandBus()->handle($oCommand);
        
        $bScheduleSlotExists = (bool) $oDatabase->fetchColumn('SELECT count(*) 
                                                FROM bolt_bm_schedule_slot 
                                                WHERE schedule_id = ? 
                                                and is_available = true and is_excluded = true and is_override = true 
                                                ',[$iScheduleId],0);
        
        $this->assertTrue($bScheduleSlotExists,'Rule has not been linked to schedule');
    
        
    }
    
    
    public function RemoveFromScheduleTest($iScheduleId, $iRuleId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        
        $oCommandBus = $oContainer->getCommandBus(); 
       
        $oCommand = new RemoveRuleFromScheduleCommand($iScheduleId, $iRuleId);
       
        $this->getCommandBus()->handle($oCommand);
        
        $this->assertTrue(true);
       
    }
    
    
    public function AssignToTeam($iMemberId, $iTeamId, $iScheduleId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        
        $oCommandBus = $oContainer->getCommandBus(); 
       
        $oCommand = new AssignTeamMemberCommand($iMemberId, $iTeamId, $iScheduleId);
         
        $this->getCommandBus()->handle($oCommand);
        
        
        $iInserted = (integer) $oDatabase->fetchColumn('SELECT count(*) 
                                                FROM bolt_bm_schedule_team_members 
                                                WHERE schedule_id = ? and membership_id = ? and team_id = ? 
                                                ',[$iScheduleId,$iMemberId,$iTeamId],0);
        
        $this->assertEquals(1,$iInserted);
        
    }
    
    public function WithdrawlToTeam($iMemberId, $iTeamId, $iScheduleId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        
        $oCommandBus = $oContainer->getCommandBus(); 
       
        $oCommand = new WithdrawlTeamMemberCommand($iMemberId, $iTeamId, $iScheduleId);
         
        $this->getCommandBus()->handle($oCommand);
        
        
        $bInserted = (integer) $oDatabase->fetchColumn('SELECT count(*) 
                                                FROM bolt_bm_schedule_team_members 
                                                WHERE schedule_id = ? and membership_id = ? and team_id = ? 
                                                ',[$iScheduleId,$iMemberId,$iTeamId],0);
        
        $this->assertEquals(0,$bInserted);
        
    }
    
}
/* end of file */
