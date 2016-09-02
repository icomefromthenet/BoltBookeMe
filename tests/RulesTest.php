<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\AssignRuleToScheduleCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\CronToQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\ParsedRange;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


class RulesTest extends ExtensionTest
{
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      $oService = $this->getTestAPI();
      
      $oService->addCalenderYears(5);
      $oNow   = $this->getNow();
      
      $iFiveMinuteTimeslot    = $oService->addTimeslot(5,$oNow->format('Y'));
      $iTenMinuteTimeslot     = $oService->addTimeslot(10,$oNow->format('Y'));
      $iSevenMinuteTimeslot    = $oService->addTimeslot(7,$oNow->format('Y'));

      $oService->toggleSlotAvability($iTenMinuteTimeslot);    
  
      $iMemberOne   = $oService->registerMembership('Bob Builder');
      $iMemberTwo   = $oService->registerMembership('Bob Assistant');
      $iMemberThree = $oService->registerMembership('Bill Builder');
      $iMemberFour  = $oService->registerMembership('Bill Assistant');
    
      $iTeamOne     = $oService->registerTeam('Bobs Team');
      $iTeamTwo     = $oService->registerTeam('Bills Team');
      
            
      $this->aDatabaseId = [
        'five_minute'    => $iFiveMinuteTimeslot,
        'ten_minute'     => $iTenMinuteTimeslot,
        'seven_minute'   => $iSevenMinuteTimeslot,
        'member_one'     => $iMemberOne,
        'member_two'     => $iMemberTwo,
        'member_three'   => $iMemberThree,
        'member_four'    => $iMemberFour,
        'team_two'       => $iTeamTwo,
        'team_one'       => $iTeamOne,
      ];
    
      
      
   }  
   
    /**
    * @group Rule
    */ 
   public function testRule()
   {
       $this->ProviderTest();
       $this->SegmentParserEntity();
       $this->SegmentParserEntityPassValidate();
       $this->SegmentParserEntityFailsValidate();
       $this->SegmentParserMonthSegment();
       $this->SegmentParserDayMonthSegment();
       $this->SegmentParserDayWeekSegment();
       $this->SegmentParserWeekYearSegment();
       $this->AssignRuleToScheduleCommand();
       $this->SlotFinderTest();
       $this->NewRuleTest();
       $this->NewSingleDayRuleTest();
   }
   
   
   
    protected function ProviderTest()
    {
       
       $oContainer = $this->getContainer();
       $oStartDate = new DateTime();
       $oStopDate  = new DateTime(); 
       $oStopDate->setDate($oStartDate->format('Y'),'12','31');
       
       $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
       $oSlotFinder      = $oContainer['bm.slotFinder'];
       
       $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\SegmentParser',$oSegmentParser);
       $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\SlotFinder',$oSlotFinder);
       
       $oCronToQuery = $oContainer['bm.cronToQuery'];
       
       $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Cron\CronToQuery',$oCronToQuery);
       
    }
    
  
    protected function SegmentParserEntity()
    {
        $oContainer = $this->getContainer();
        
        $iSegmentOrder = 1;
        $iRangeOpen  = 1;
        $iRangeClose = 100;
        $iModVaue    = 1;
        $sRangeType = 'minute';
        
        
        # Test parsed range accessors
        $oRange = new ParsedRange($iSegmentOrder,$iRangeOpen,$iRangeClose,$iModVaue, $sRangeType); 
        
        $this->assertEquals($iSegmentOrder, $oRange->getSegmentOrder());
        $this->assertEquals($iRangeOpen,  $oRange->getRangeOpen());
        $this->assertEquals($iRangeClose, $oRange->getRangeClose() );
        $this->assertEquals($iModVaue, $oRange->getModValue() );
        $this->assertEquals($sRangeType,$oRange->getRangeType());
        
    }
    
   
    protected function SegmentParserEntityPassValidate()
    {
        $oContainer = $this->getContainer();
        $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
        
        $iSegmentOrder = 1;
        $iRangeOpen  = 1;
        $iRangeClose = 100;
        $iModVaue    = 1;
        $sRangeType = 'minute';
        
        
        # Test parsed range accessors
        $oRange = new ParsedRange($iSegmentOrder,$iRangeOpen,$iRangeClose,$iModVaue, $sRangeType); 
        
        $this->assertEquals($iSegmentOrder, $oRange->getSegmentOrder());
        $this->assertEquals($iRangeOpen,  $oRange->getRangeOpen());
        $this->assertEquals($iRangeClose, $oRange->getRangeClose() );
        $this->assertEquals($iModVaue, $oRange->getModValue() );
        $this->assertEquals($sRangeType,$oRange->getRangeType());
        
        $this->assertTrue($oRange->validate());
        
    }
    
    protected function SegmentParserEntityFailsValidate()
    {
        $oContainer       = $this->getContainer();
        $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
        
        $iSegmentOrder = -1;
        $iRangeOpen  = 1;
        $iRangeClose = 100;
        $iModVaue    = 1;
        $sRangeType = 'minute';
        
        
        # Test parsed range accessors
        $oRange = new ParsedRange($iSegmentOrder,$iRangeOpen,$iRangeClose,$iModVaue, $sRangeType); 
        
        $this->assertEquals($iSegmentOrder, $oRange->getSegmentOrder());
        $this->assertEquals($iRangeOpen,  $oRange->getRangeOpen());
        $this->assertEquals($iRangeClose, $oRange->getRangeClose() );
        $this->assertEquals($iModVaue, $oRange->getModValue() );
        $this->assertEquals($sRangeType,$oRange->getRangeType());
        
        try {
            $oRange->validate();
            $this->assertTrue(false,'ParsedRange shoud not passed validation');
            
        } catch(ValidationException $e) {
            $this->assertTrue(true);
        }
        
        
    }
    
   
    protected function SegmentParserMonthSegment()
    {
        $oContainer       = $this->getContainer();
        $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
        
        $sCronType  = ParsedRange::TYPE_MONTH;
        $sCronExpr = '*';
        $sCronExprA = '1-12/2';
        $sCronExprB = '2/2';
        $sCronExprC = '10-12,7-12';
        $sCronExprD = '3';
        $sCronExprE = '*/3';
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExpr);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(12,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
       
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprA);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(12,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprB);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(2,$aRange[0]->getRangeOpen());
        $this->assertEquals(12,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprC);    
        
        $this->assertCount(2,$aRange);
        $this->assertEquals(10,$aRange[0]->getRangeOpen());
        $this->assertEquals(12,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        $this->assertEquals(7,$aRange[1]->getRangeOpen());
        $this->assertEquals(12,$aRange[1]->getRangeClose());
        $this->assertEquals(1,$aRange[1]->getModValue());
    
        
         
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprD);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(3,$aRange[0]->getRangeOpen());
        $this->assertEquals(3,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        
        
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprE);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(12,$aRange[0]->getRangeClose());
        $this->assertEquals(3,$aRange[0]->getModValue());
       
      
      
    }
    
   
    protected function SegmentParserDayMonthSegment()
    {
        $oContainer       = $this->getContainer();
        $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
        
        $sCronType  = ParsedRange::TYPE_DAYOFMONTH;
        $sCronExpr = '*';
        $sCronExprA = '1-20/2';
        $sCronExprB = '2/2';
        $sCronExprC = '1-6,7-12';
        $sCronExprD = '3';
        $sCronExprE = '*/3';
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExpr);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(31,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
       
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprA);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(20,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprB);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(2,$aRange[0]->getRangeOpen());
        $this->assertEquals(31,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprC);    
        
        $this->assertCount(2,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(6,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        $this->assertEquals(7,$aRange[1]->getRangeOpen());
        $this->assertEquals(12,$aRange[1]->getRangeClose());
        $this->assertEquals(1,$aRange[1]->getModValue());
    
        
         
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprD);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(3,$aRange[0]->getRangeOpen());
        $this->assertEquals(3,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        
        
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprE);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(31,$aRange[0]->getRangeClose());
        $this->assertEquals(3,$aRange[0]->getModValue());
       
      
      
    }
    
    
    protected function SegmentParserDayWeekSegment()
    {
        $oContainer       = $this->getContainer();
        $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
        
        $sCronType  = ParsedRange::TYPE_DAYOFWEEK;
        $sCronExpr = '*';
        $sCronExprA = '0-6/2';
        $sCronExprB = '6/2';
        $sCronExprC = '1-6,0-5';
        $sCronExprD = '3';
        $sCronExprE = '*/3';
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExpr);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(0,$aRange[0]->getRangeOpen());
        $this->assertEquals(6,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
       
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprA);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(0,$aRange[0]->getRangeOpen());
        $this->assertEquals(6,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprB);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(6,$aRange[0]->getRangeOpen());
        $this->assertEquals(6,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprC);    
        
        $this->assertCount(2,$aRange);
        $this->assertEquals(1,$aRange[0]->getRangeOpen());
        $this->assertEquals(6,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        $this->assertEquals(0,$aRange[1]->getRangeOpen());
        $this->assertEquals(5,$aRange[1]->getRangeClose());
        $this->assertEquals(1,$aRange[1]->getModValue());
    
        
         
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprD);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(3,$aRange[0]->getRangeOpen());
        $this->assertEquals(3,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        
        
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprE);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(0,$aRange[0]->getRangeOpen());
        $this->assertEquals(6,$aRange[0]->getRangeClose());
        $this->assertEquals(3,$aRange[0]->getModValue());
       
      
      
    }
    
    protected function SegmentParserWeekYearSegment()
    {
        $oContainer       = $this->getContainer();
        $oSegmentParser   = $oContainer['bm.cronSegmentParser'];
        
        $sCronType  = ParsedRange::TYPE_WEEKOFYEAR;
        $sCronExpr = '*';
        $sCronExprA = '0-52/2';
        $sCronExprB = '46/2';
        $sCronExprC = '5-34,35-52';
        $sCronExprD = '23';
        $sCronExprE = '*/3';
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExpr);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(0,$aRange[0]->getRangeOpen());
        $this->assertEquals(52,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
       
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprA);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(0,$aRange[0]->getRangeOpen());
        $this->assertEquals(52,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprB);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(46,$aRange[0]->getRangeOpen());
        $this->assertEquals(52,$aRange[0]->getRangeClose());
        $this->assertEquals(2,$aRange[0]->getModValue());
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprC);    
        
        $this->assertCount(2,$aRange);
        $this->assertEquals(5,$aRange[0]->getRangeOpen());
        $this->assertEquals(34,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        $this->assertEquals(35,$aRange[1]->getRangeOpen());
        $this->assertEquals(52,$aRange[1]->getRangeClose());
        $this->assertEquals(1,$aRange[1]->getModValue());
    
        
         
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprD);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(23,$aRange[0]->getRangeOpen());
        $this->assertEquals(23,$aRange[0]->getRangeClose());
        $this->assertEquals(1,$aRange[0]->getModValue());
        
        
        
        $aRange = $oSegmentParser->parseSegment($sCronType,$sCronExprE);    
        
        $this->assertCount(1,$aRange);
        $this->assertEquals(0,$aRange[0]->getRangeOpen());
        $this->assertEquals(52,$aRange[0]->getRangeClose());
        $this->assertEquals(3,$aRange[0]->getModValue());
       
      
      
    }
    
    
    protected function AssignRuleToScheduleCommand()
    {
        
        $iRuleDatabaseId        = 1;
        $iScheduleDatabaseId    = 2;
        $bRolloverFlag          = true;
        
        $oCommand = new AssignRuleToScheduleCommand($iScheduleDatabaseId, $iRuleDatabaseId, $bRolloverFlag);
        
        
        $this->assertEquals($iRuleDatabaseId, $oCommand->getRuleId());
        $this->assertEquals($iScheduleDatabaseId, $oCommand->getScheduleId());
        $this->assertEquals($bRolloverFlag, $oCommand->getRolloverFlag());
        
        $aRules     = $oCommand->getRules();
        $aData      = $oCommand->getData();
        $oValidator = new Validator($aData);
            
        
        $oValidator->rules($aRules);
        
        $this->assertTrue($oValidator->validate(),'AssignRuleToScheduleCommand is invalid when should be valid');
        
    }
    
    /**
    * @group Rule
    */
    public function SlotFinderTest()
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oSlotFinder = $oContainer['bm.slotFinder'];
        $oNow        = $this->getNow();
        
        $oRangeMonth      = new ParsedRange(1,10,12,1,ParsedRange::TYPE_MONTH);
        $oRangeDayOfMonth = new ParsedRange(2,1,14,1,ParsedRange::TYPE_DAYOFMONTH);
        $oRangeDayOfWeek = new ParsedRange(3,0,6,1,ParsedRange::TYPE_DAYOFWEEK);
        $oRangeWeekofYear = new ParsedRange(1,0,52,1,ParsedRange::TYPE_WEEKOFYEAR);
     
        // from june to the end of the year
        $oStartDate  = clone $oNow;
        $oEndDate    = clone $oNow;
        $oStartDate->setDate($oStartDate->format('Y'),'06','1');
        $oEndDate->setDate($oStartDate->format('Y'),'12','31');
        $iOpeningTimeslot = 1000;
        $iClosingTimeslot = 1440;
        $iTimeslotId = $this->aDatabaseId['ten_minute'];
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 1, $iTimeslotId, $iOpeningTimeslot, $iClosingTimeslot,'*', '1-14','10-12','*');
        
        $oSlotFinder->findSlots($oCommand,array($oRangeMonth, $oRangeDayOfMonth, $oRangeDayOfWeek,$oRangeWeekofYear));
        
        $oDateType = Type::getType(Type::DATETIME);
        
        $oOpeningFirstSlot = $oDateType->convertToPHPValue($oDatabase->fetchColumn("SELECT min(opening_slot) FROM bm_tmp_rule_series",[],0), $oDatabase->getDatabasePlatform());
        $oClosingLastSlot = $oDateType->convertToPHPValue($oDatabase->fetchColumn("SELECT max(closing_slot) FROM bm_tmp_rule_series",[],0), $oDatabase->getDatabasePlatform());
        
        $this->assertEquals('01-10-2016',$oOpeningFirstSlot->format('d-m-Y'),'Opening slot has wrong date');
        $this->assertEquals('15-11-2016',$oClosingLastSlot->format('d-m-Y'),'Closing slot has wrong date');
        
        $this->assertEquals('16:40',$oOpeningFirstSlot->format('H:i'),'Opening minute has wrong date');
        $this->assertEquals('00:00',$oClosingLastSlot->format('H:i'),'Closing minute has wrong date');
        
    }
    
    

    /**
    * @group Rule
    */ 
    public function NewRuleTest()
    {
       
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oNow        = $this->getNow();
     
        $oStartDate  = clone $oNow;
        $oEndDate    = clone $oNow;
        $oStartDate->setDate($oStartDate->format('Y'),'06','1');
        $oEndDate->setDate($oStartDate->format('Y'),'12','31');
        $iOpeningTimeslot = 1000;
        $iClosingTimeslot = 1440;
        $iTimeslotId = $this->aDatabaseId['ten_minute'];
        $sDescription = 'A short rule description';
        $sName        = 'A short rule name';
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 1, $iTimeslotId, $iOpeningTimeslot, $iClosingTimeslot,'*', '1-14','10-12','*',false,$sName, $sDescription);
        
        $this->getCommandBus()->handle($oCommand);
        
        $oDateType = Type::getType(Type::DATETIME);
        
        $oOpeningFirstSlot = $oDateType->convertToPHPValue($oDatabase->fetchColumn("SELECT min(opening_slot) FROM bm_tmp_rule_series",[],0), $oDatabase->getDatabasePlatform());
        $oClosingLastSlot = $oDateType->convertToPHPValue($oDatabase->fetchColumn("SELECT max(closing_slot) FROM bm_tmp_rule_series",[],0), $oDatabase->getDatabasePlatform());
        
        $this->assertEquals('01-10-2016',$oOpeningFirstSlot->format('d-m-Y'),'Opening slot has wrong date');
        $this->assertEquals('15-11-2016',$oClosingLastSlot->format('d-m-Y'),'Closing slot has wrong date');
        
        $this->assertEquals('16:40',$oOpeningFirstSlot->format('H:i'),'Opening minute has wrong date');
        $this->assertEquals('00:00',$oClosingLastSlot->format('H:i'),'Closing minute has wrong date');
       
    }
    
    
    /**
    * @group Rule
    */ 
    public function NewSingleDayRuleTest()
    {
       
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        $oNow        = $this->getNow();
     
        $oStartDate  = clone $oNow;
        $oEndDate    = clone $oNow;
        $oStartDate->setDate($oStartDate->format('Y'),'06','1');
        $oEndDate->setDate($oStartDate->format('Y'),'06','1');
        $iOpeningTimeslot = 1000;
        $iClosingTimeslot = 1440;
        $iTimeslotId = $this->aDatabaseId['ten_minute'];
        $sDescription = 'A short rule description';
        $sName        = 'A short rule name';
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 1, $iTimeslotId, $iOpeningTimeslot, $iClosingTimeslot,'*', '*','*','*',true,$sName, $sDescription);
        
        $this->getCommandBus()->handle($oCommand);
        
        $oDateType = Type::getType(Type::DATETIME);
        
        $oOpeningFirstSlot = $oDateType->convertToPHPValue($oDatabase->fetchColumn("SELECT min(opening_slot) FROM bm_tmp_rule_series",[],0), $oDatabase->getDatabasePlatform());
        $oClosingLastSlot = $oDateType->convertToPHPValue($oDatabase->fetchColumn("SELECT max(closing_slot) FROM bm_tmp_rule_series",[],0), $oDatabase->getDatabasePlatform());
        
        $this->assertEquals('01-06-2016',$oOpeningFirstSlot->format('d-m-Y'),'Opening slot has wrong date');
        $this->assertEquals('02-06-2016',$oClosingLastSlot->format('d-m-Y'),'Closing slot has wrong date');
        
        $this->assertEquals('16:40',$oOpeningFirstSlot->format('H:i'),'Opening minute has wrong date');
        $this->assertEquals('00:00',$oClosingLastSlot->format('H:i'),'Closing minute has wrong date');
       
    }
    
    
  
    
}
/* end of file */
