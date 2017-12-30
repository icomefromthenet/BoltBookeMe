<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Datatable;

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
