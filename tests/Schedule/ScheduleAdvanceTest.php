<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Schedule;

use DateTime;
use Doctrine\DBAL\Types\Type;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\AssignRuleToScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RemoveRuleFromScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\AssignTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\WithdrawlTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleException;



class ScheduleAdvanceTest extends ExtensionTest
{
    
    
    protected $aDatabaseId = [];
    
    
    
   protected function handleEventPostFixtureRun()
   {
      
        $oContainer = $this->getContainer();
        $oService   = $this->getTestAPI();
        $oNow       = $this->getNow();
        $oDatabase  = $this->getDatabaseAdapter();
        
        
        
        $aSql   = []; 
        $aSql[] = " UPDATE  bolt_bm_schedule_slot ";
        $aSql[] = " SET `is_available` = false, `is_excluded` = false, `is_override` = false ";
        
        $sSql = implode(PHP_EOL,$aSql);
        
        $oDatabase->executeUpdate($sSql, [], []);
        
        
        
        
        $aSql   = []; 
        $aSql[] = " DELETE FROM  bolt_bm_rule_schedule WHERE 1=1";
        
        $sSql = implode(PHP_EOL,$aSql);
        
        $oDatabase->executeUpdate($sSql, [], []);
      
      
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
        $iTeamTwoId        = $this->aDatabaseId['team_two'];
        
        
        $this->ApplyRulesTest($iScheduleId, $iRuleOneId,$iRuleTwoId,$iRuleThreeId);
        $this->RefreshScheduleTest($iScheduleId);
        $this->RemoveFromScheduleTest($iScheduleId, $iRuleOneId);
        $this->AssignToTeam($iMemberOneId,$iTeamTwoId);
        $this->WithdrawlToTeam($iMemberOneId,$iTeamTwoId);
       
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
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand = new RefreshScheduleCommand($iScheduleId, false);
       
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
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand = new RemoveRuleFromScheduleCommand($iScheduleId, $iRuleId);
       
        $this->getCommandBus()->handle($oCommand);
        
        $this->assertTrue(true);
       
    }
    
    
    public function AssignToTeam($iMemberId, $iTeamId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand = new AssignTeamMemberCommand($iMemberId, $iTeamId);
         
        $this->getCommandBus()->handle($oCommand);
        
        
        $iInserted = (integer) $oDatabase->fetchColumn('SELECT count(*) 
                                                FROM bolt_bm_schedule_team_members 
                                                WHERE membership_id = ? and team_id = ? 
                                                ',[$iMemberId,$iTeamId],0);
        
        $this->assertEquals(1,$iInserted);
        
    }
    
    public function WithdrawlToTeam($iMemberId, $iTeamId)
    {
        $oContainer  = $this->getContainer();
        $oDatabase   = $this->getDatabaseAdapter();
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand = new WithdrawlTeamMemberCommand($iMemberId, $iTeamId);
         
        $this->getCommandBus()->handle($oCommand);
        
        
        $bInserted = (integer) $oDatabase->fetchColumn('SELECT count(*) 
                                                FROM bolt_bm_schedule_team_members 
                                                WHERE membership_id = ? and team_id = ? 
                                                ',[$iMemberId,$iTeamId],0);
        
        $this->assertEquals(0,$bInserted);
        
    }
    
}
/* end of file */
