<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Schedule;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock\MockRuleBuilderTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock\MockRuleBuilder;



class RuleBuilderTest extends ExtensionTest
{
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      
   }  
   
    /**
    * @group schedule
    */ 
   public function testRuleBuilder()
   {
        $iScheduleId = 1;
        $iTimeslotId = 1;
        
        
        $this->RuleTraitTest($iScheduleId, $iTimeslotId);
        $this->RuleBuildNewRuleCommandTest($iScheduleId, $iTimeslotId);


        $this->TestBaseRulesNoSyntaxErrors();
   }
   
   
   protected function RuleTraitTest($iScheduleId, $iTimeslotId)
   {
        $oMockTrait = new MockRuleBuilderTrait();
        
        $oSchedule  = new ScheduleEntity();
        $oSchedule->schedule_id = $iScheduleId;
        $oSchedule->timeslot_id = $iTimeslotId;
        
        $oMockTrait->forSchedule($oSchedule);
        
        $oStartDate = new DateTime('now');
        $oEndDate   = new DateTime('now');
        
        
        $oMockTrait->forDateBetween($oStartDate,$oEndDate);
        
        $oStartTime = new DateTime('now'); 
        $oStartTime->setTime(0,0,0);
        
        $oEndTime   = new DateTime('now'); 
        $oEndTime->setTime(9,0,0);
        
        $oMockTrait->forTimeBetween($oStartTime, $oEndTime);
        
        $sRuleName = 'Rule A';
        $sRuleDescription = 'Rule A Description';
        
        $oMockTrait->forRulename($sRuleName);
        $oMockTrait->forRuleDescription($sRuleDescription);
        
        
        $oMockTrait->runTest($this, $oSchedule, $oStartDate, $oEndDate, $oStartTime, $oEndTime, $sRuleName, $sRuleDescription);
       
   }
    
    
    protected function RuleBuildNewRuleCommandTest($iScheduleId, $iTimeslotId)
    {
       $oDatabase   = $this->getDatabaseAdapter();
       $aTableNames = $this->getTableNames();
       
       $oRuleBuilder = new MockRuleBuilder($oDatabase, $aTableNames);
        
        
       $oSchedule  = new ScheduleEntity();
       $oSchedule->schedule_id = $iScheduleId;
       $oSchedule->timeslot_id = $iTimeslotId;
       
       $oRuleBuilder->forSchedule($oSchedule);
       
       $oStartDate = new DateTime('now');
       $oEndDate   = new DateTime('now +1 day');
       
       
       $oRuleBuilder->forDateBetween($oStartDate,$oEndDate);
       
       $oStartTime = new DateTime('now'); 
       $oStartTime->setTime(0,0,0);
       
       $oEndTime   = new DateTime('now'); 
       $oEndTime->setTime(9,0,0);
       
       $oRuleBuilder->forTimeBetween($oStartTime, $oEndTime);
       
       $sRuleName = 'Rule A';
       $sRuleDescription = 'Rule A Description';
       
       $oRuleBuilder->forRulename($sRuleName);
       $oRuleBuilder->forRuleDescription($sRuleDescription);
    
    
        $oCommand =     $oRuleBuilder->getNewRuleCommand();
       
        $this->assertInstanceOf('Bolt\\Extension\\IComeFromTheNet\\BookMe\\Model\\Rule\\Command\\CreateRuleCommand' ,$oCommand);
       
        // Defined in this test case
        $this->assertEquals($oStartDate, $oCommand->getCalendarStart());
        $this->assertEquals($oEndDate, $oCommand->getCalendarEnd());
        $this->assertEquals($iTimeslotId, $oCommand->getTimeSlotId());
        $this->assertEquals($sRuleName, $oCommand->getRuleName());
        $this->assertEquals($sRuleDescription, $oCommand->getRuleDescription());
       
        // Come from mock class
        $this->assertEquals(500,$oCommand->getRuleTypeId());
        $this->assertEquals('*',$oCommand->getRuleRepeatMinute());
        $this->assertEquals('*',$oCommand->getRuleRepeatHour());
        $this->assertEquals('6',$oCommand->getRuleRepeatDayOfWeek());
        $this->assertEquals('1-31',$oCommand->getRuleRepeatDayOfMonth());
        $this->assertEquals('5-8',$oCommand->getRuleRepeatMonth());
        $this->assertEquals('10-52',$oCommand->getRuleRepeatWeekOfYear());
        $this->assertEquals(false,$oCommand->getIsSingleDay());
        
        // Looked up
        $this->assertEquals(0,$oCommand->getOpeningSlot());
        $this->assertEquals(540,$oCommand->getClosingSlot());
        
        
        
       
    }
    
    
    protected function TestBaseRulesNoSyntaxErrors()
    {
        
        $oHolidayMock = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\HolidayRuleNode')
                             ->disableOriginalConstructor()
                             ->getMock();
        
        $oBreakMock = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\BreakRuleNode')
                            ->disableOriginalConstructor()
                            ->getMock();
        
  
        $oWorkDayMock = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\WorkdayRuleNode')
                            ->disableOriginalConstructor()
                             ->getMock();
        
    
        $oOvertimeMock = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\OvertimeRuleNode')
                            ->disableOriginalConstructor()
                             ->getMock();
        
    
        $oSurchargeMock = $this->getMockBuilder('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\SurchargeRuleNode')
                            ->disableOriginalConstructor()
                             ->getMock();
        
        
        
    }
  
    
}
/* end of file */
