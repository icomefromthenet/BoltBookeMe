<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


class CustomRepoTest extends ExtensionTest
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
        
      $iRepeatWorkDayRule    = $oService->createRepeatingWorkDayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'1-5','*','2-12','*','Repeat Work Day Rule','short rule description');
      $iSingleWorkDayRule    = $oService->createSingleWorkDayRule($oSingleDate,$iFiveMinuteTimeslot,$iFivePmSlot,$iTenPmSlot,'Single Workday rule','short rule description'); 
      
      $iMidaySlot = (12*12)*5;
      $iOnePmSlot = (12*13)*5;
      
      $iEightPmSlot  = (12*18)*5;
      $iEightThirtyPmSlot = ((12*18) + 6)*5;
      
      $iRepeatBreakRule      = $oService->createRepeatingBreakRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iMidaySlot,$iOnePmSlot,'1-5','*','2-12','*','Repeat Break Rule','short rule description');
      $iSingleBreakRule      = $oService->createSingleBreakRule($oSingleDate,$iFiveMinuteTimeslot,$iEightPmSlot,$iEightThirtyPmSlot,'Single Break Rule','short rule description'); 
            
            
      $iRepeatHolidayRule    = $oService->createRepeatingHolidayRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*','*','Repeat Holiday Rule','short rule description');    
      $iSingleHolidayRule      = $oService->createSingleHolidayRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'Single Holiday Rule','short rule description');             
    
    
      $iRepeatOvertimeRule   = $oService->createRepeatingOvertimeRule($oDayWorkDayRuleStart,$oDayWorkDayRuleEnd,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'*','28-30','*','*','Repeat Overtime Rule','short rule description');
      $iSingleOvertimeRule   = $oService->createSingleOvertmeRule($oHolidayStart,$iFiveMinuteTimeslot,$iNineAmSlot,$iFivePmSlot,'Single Overtime Rule','short rule description');
      
      
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
    
     
      
      
      // Create some customers
      
      $iCustomerOneId     = $oService->createCustomer('Bob', 'Builder', 'bob@builder.com', '0404555555', '98172762', 'Bob Address Line One', 'Bob Address Line Two', 'Company One');
      $iCustomerTwoId     = $oService->createCustomer('Steve', 'Builder', 'seteve@builder.com', '0404555556', '98172762' , 'Steve Address Line One', 'Steve Address Line Two', 'Company two');
      $iCustomerThreeId   = $oService->createCustomer('Karen', 'Builder', 'karen@builder.com', '0404555557', '98172762' , 'Karen Address Line One', 'Karen Address Line Two', 'Company three');
   
      
       // save identifiers for use below    
            
      $this->aDatabaseId = [
        'start_year'             => $oStartYear,
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
    public function testCustomRepo()
    {
       
       //$this->RuleRepoTest($this->aDatabaseId['work_repeat']);
       
       $this->ScheduleRepoTest($this->aDatabaseId['schedule_member_one'], $this->aDatabaseId['five_minute'],$this->aDatabaseId['member_one']);
       
       //$this->MemberRepoTest($this->aDatabaseId['member_one']);
       
       //$this->CustomerRepoTest($this->aDatabaseId['customer_1']);
       
       //$this->CalendarYearRepoTest($this->aDatabaseId['start_year']);
       
       //$this->TimeslotRepoTest($this->aDatabaseId['ten_minute']);
       
       //$this->RuleTypeRepoTest();
       
       //$this->TeamRepoTest($this->aDatabaseId['team_one']);
    }
    
    protected function RuleRepoTest($iRuleId)
    {
        $oNow         = $this->getNow();
        $oApp = $this->getContainer();
    
        $oRuleRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleRepository',$oRuleRepo);
        
        $oRuleQueryBuilder = $oRuleRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleQueryBuilder',$oRuleQueryBuilder);
        
        
        
        $oWorkDayRule = $oRuleRepo->find($iRuleId);
  
        $this->assertEquals($iRuleId,$oWorkDayRule->getRuleId());
        $this->assertEquals("Repeat Work Day Rule",$oWorkDayRule->getRuleName());
        $this->assertEquals(false, $oWorkDayRule->getSingleDayFlag());
        $this->assertEquals($oNow->format('Y'),$oWorkDayRule->getCalendarYear());
        $this->assertEquals(540,$oWorkDayRule->getDayOpenSlot());
        $this->assertEquals(1020, $oWorkDayRule->getDayCloseSlot());
        $this->assertEquals($oNow->format('Y')."-12-31" , $oWorkDayRule->getEndAt()->format('Y-m-d'));
        $this->assertEquals($oNow->format('Y')."-01-01" , $oWorkDayRule->getStartFrom()->format('Y-m-d'));
        $this->assertEquals('short rule description', $oWorkDayRule->getRuleDescription());
        $this->assertEquals("2-12", $oWorkDayRule->getRepeatMonth());
        $this->assertEquals("*", $oWorkDayRule->getRepeatDayOfMonth());
        $this->assertEquals("1-5", $oWorkDayRule->getRepeatDayOfWeek());
        $this->assertEquals("*", $oWorkDayRule->getRepeatWeekOfYear());
        $this->assertEquals(1, $oWorkDayRule->getTimeslotId());
        $this->assertEquals(1, $oWorkDayRule->getRuleTypeId());
        
        
        
    }
    
    
  
    public function ScheduleRepoTest($iScheduleId,$iTimeSlotId, $iMemberId)
    {
        $oApp = $this->getContainer();
        $oNow = $this->getNow();
    
        $oRepo = $oApp['bm.repo.schedule'];
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleQueryBuilder',$oQueryBuilder);
        
        
        
        $oSchedule = $oRepo->find($iScheduleId);
  
        $this->assertEquals($iScheduleId,$oSchedule->getScheduleId());
        $this->assertEquals($iTimeSlotId, $oSchedule->getTimeslotId());
        $this->assertEquals($iMemberId, $oSchedule->getMemberId());
        
        $this->assertNotEmpty($oSchedule->getCalendarYear());
        $this->assertTrue($oSchedule->getCarryOver());
        $this->assertNotEmpty($oSchedule->getRegisteredDate());
        $this->assertEmpty($oSchedule->getCloseDate());
        
        // Test the Query Builder Filters
        $oQuery = $oRepo->createQueryBuilder('a');
        
        $oQuery->filterByCalendarYear('a',2017);
        $this->assertContains('a.calendar_year = :iCalYear',$oQuery->getSQL());
        
        $oQuery = $oRepo->createQueryBuilder('a');
    
        $oQuery->filterByScheduleOpen('a',2017);
        $this->assertContains('a.close_date IS NULL',$oQuery->getSQL());
    
    
        $oQuery = $oRepo->createQueryBuilder('a');
    
        $oQuery->filterByscheduleClosed('a',2017);
        $this->assertContains('a.close_date IS NOT NULL',$oQuery->getSQL());
        
        $oQuery = $oRepo->createQueryBuilder('a');
    
        $oQuery->whereScheduleClosedDuringCalenderYear('a',2017);
        $this->assertContains('a.calendar_year = :iCalYear',$oQuery->getSQL());
        $this->assertContains('a.close_date IS NOT NULL',$oQuery->getSQL());
        
        $oQuery = $oRepo->createQueryBuilder('a');
    
        $oQuery->whereScheduleOpenDuringCalenderYear('a',2017);
        $this->assertContains('a.calendar_year = :iCalYear',$oQuery->getSQL());
        $this->assertContains('a.close_date < :sCloseDate OR a.close_date IS NULL',$oQuery->getSQL());
    
        $oQuery = $oRepo->createQueryBuilder('a');
    
        $oQuery->withMember('a','b');
        $this->assertContains('INNER JOIN bolt_bm_schedule_membership b ON a.membership_id = b.membership_id',$oQuery->getSQL());
        
        $oQuery = $oRepo->createQueryBuilder('a');
    
        $oQuery->withBoltUser('a','b');
        $this->assertContains('LEFT JOIN bolt_users b ON a.bolt_user_id = b.id', $oQuery->getSQL());
        
        // Test Repository Finders 
        
        $aResult = $oRepo->findAllSchedulesInCalYear($oNow->format('Y'));
        $this->assertCount(3,$aResult); // 3 Active Schedules
        
        
        
    }
        
    
    public function MemberRepoTest($iMemberId)
    {
        $oNow = $this->getNow();
        $oApp = $this->getContainer();
    
        $oRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MemberQueryBuilder',$oQueryBuilder);
        
        
        
        $oMember = $oRepo->find($iMemberId);
  
        $this->assertEquals($iMemberId,$oMember->getMembershipId());
        $this->assertEquals($iMemberId,$oMember->getMemberId());
        
        $this->assertEquals($oNow->format('Y-m-d'), $oMember->getRegisteredDate()->format('Y-m-d'));
        $this->assertEquals('Bob Builder',$oMember->getMemberName());
        
        
        
    }
    
    
    public function CustomerRepoTest($iCustomerId)
    {
        $oNow         = $this->getNow();
        $oApp = $this->getContainer();
    
        $oRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\CustomerQueryBuilder',$oQueryBuilder);
        
        
        
        $oCustomer = $oRepo->find($iCustomerId);
  
        $this->assertEquals($iCustomerId,$oCustomer->getCustomerId());
    
        
    }
    
    
    public function CalendarYearRepoTest(DateTime $oDate)
    {
        $oNow         = $oDate;
        $oApp = $this->getContainer();
    
        $oRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearQueryBuilder',$oQueryBuilder);
        
        $oCalYear = $oRepo->find($oNow->format('Y'));
        
        $this->assertEquals($oNow->format('Y'),$oCalYear->getCalendarYear());
        $this->assertEquals($oNow->format('Y'), $oCalYear->getStartOfYear());
        $this->assertEquals($oNow->format('Y'), $oCalYear->getEndOfYear());
        $this->assertEquals(true, $oCalYear->getCurrentYearFlag());
        
        
    }
    
    
    public function TimeslotRepoTest($iTimeslotId)
    {
        $oNow         = $oDate;
        $oApp = $this->getContainer();
    
        $oRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotQueryBuilder',$oQueryBuilder);
        
        $oTimeSlot = $oRepo->find($iTimeslotId);
    
        $this->assertEquals($iTimeslotId,$oTimeSlot->getTimeslotId());
        $this->assertEquals(10, $oTimeSlot->getSlotLength());
        $this->assertEquals(false, $oTimeSlot->getActiviteStatus());
      
    }
    
    public function RuleTypeRepoTest()
    {
        $oNow         = $oDate;
        $oApp = $this->getContainer();
    
        $oRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeQueryBuilder',$oQueryBuilder);
        
        $oRuleType = $oRepo->find(1);
    
        $this->assertEquals(1,$oRuleType->getRuleTypeId());
        $this->assertTrue($oRuleType->getRuleTypeCode() !== null);
        $this->assertTrue($oRuleType->getInclusionOverrideFlag() !== null);
        $this->assertTrue($oRuleType->getWorkDayFlag() !== null);
        $this->assertTrue($oRuleType->getExclusionFlag() !== null);
        
      
    }
    
    public function TeamRepoTest($iTeamId)
    {
        
        $oNow         = $oDate;
        $oApp = $this->getContainer();
    
        $oRepo = $oApp['storage']->getRepository('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamEntity');    
    
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamRepository',$oRepo);
        
        $oQueryBuilder = $oRepo->createQueryBuilder();
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamQueryBuilder',$oQueryBuilder);
        
        $oTeam = $oRepo->find($iTeamId);
    
        $this->assertEquals($iTeamId,$oTeam->getTeamId());
        $this->assertNotEmpty($oTeam->getRegisteredDate());
        $this->assertNotEmpty($oTeam->getTeamName());
      
        
    }
}
/* end of file */
