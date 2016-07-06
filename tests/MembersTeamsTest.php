<?php
namespace IComeFromTheNet\BookMe\Test;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterTeamCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\WithdrawlTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\AssignTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MembershipException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;



class MembersTeamsTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
      // Create the Calendar 
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      $oService->addCalenderYears(5);
      
      // Create some timeslots
      $iSixMinuteSlotId = $oService->addTimeslot(6,$oNow->format('Y'));
      
      $this->aDatabaseId  = [
          'slot_six_minute' => $iSixMinuteSlotId,
          
       ];
      
   }  
   
   
    /**
    * @group Setup
    */ 
    public function testMembershipCommands()
    {
        $iSixMinuteSlotId = $this->aDatabaseId['slot_six_minute'];
        $sMemberName      = 'Bob the Builder';
        $sTeamName        = 'Bobs Team';
        
        $iNewMemberId = $this->RegisterNewMember($sMemberName);
                      
        $iNewTeam     = $this->RegisterNewTeam($sTeamName);
       
        $this->AssignTeamMember($iNewMemberId,$iNewTeam);
        $this->WithdrawlTeamMember($iNewMemberId,$iNewTeam);
        
    }
    
    
    protected function RegisterNewMember($sMemberName)
    {
        $oContainer  = $this->getContainer();
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new RegisterMemberCommand($sMemberName);
       
          try {
        
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
     
        
        $iNewMemberId = $oCommand->getMemberId();
        
        $this->assertNotEmpty($iNewMemberId,'The new member command failed to return new member database id');
        
        
        // Check if member exisys
        
        $aResult = $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                                from bolt_bm_schedule_membership 
                                                where membership_id = ? ",[$iNewMemberId],[]);
       
       
        $this->assertEquals($iNewMemberId,$aResult['membership_id'],'New member could not be found in database');
        $this->assertEquals($sMemberName,$aResult['member_name'],'New member names not match in database'); 
        
        return $iNewMemberId;
        
    }
    
    
    protected function RegisterNewTeam($sTeamName)
    {
        
        $oContainer  = $this->getContainer();
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new RegisterTeamCommand($sTeamName);
       
        try {
        
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
        
        $iNewTeamId = $oCommand->getTeamId();
        
        $this->assertNotEmpty($iNewTeamId,'The new team command failed to return new team database id');
        
        // Check if member exisys
        
        $aResult =  $this->getDatabaseAdapter()
                              ->fetchAssoc("select *
                                            from bolt_bm_schedule_team 
                                            where team_id = ? ",[$iNewTeamId],[]);
       
       
        $this->assertEquals($iNewTeamId,$aResult['team_id'],'New team could not be found in database'); 
        $this->assertEquals($sTeamName,$aResult['team_name'],'Team name does not match database');
        
        return $iNewTeamId;
        
    }
    
    
    public function AssignTeamMember($iMemberId,$iTeamId)
    {
        
        $oContainer  = $this->getContainer();
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new AssignTeamMemberCommand($iMemberId,$iTeamId);
       
         try {
        
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
    
         $bFound = (bool) $this->getDatabaseAdapter()
                               ->fetchColumn("select 1
                                                from bolt_bm_schedule_team_members 
                                                where membership_id = ? and team_id = ? ",[$iMemberId,$iTeamId],0,[]);
       
       
        $this->assertTrue($bFound,'Unable to assign a member to team');
        
        
    }
    
    
    public function WithdrawlTeamMember($iMemberId,$iTeamId)
    {
        
        $oContainer  = $this->getContainer();
        
        $oCommandBus = $this->getCommandBus(); 
       
        $oCommand  = new WithdrawlTeamMemberCommand($iMemberId,$iTeamId);
       
         try {
        
            $oCommandBus->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            var_dump($e->getValidationFailures());
            
            $this->assertFalse(true,'failed validation');
        }
    
        $bFound = (bool) $this->getDatabaseAdapter()
                              ->fetchColumn("select 1
                                             from bolt_bm_schedule_team_members 
                                             where membership_id = ? and team_id = ?",[$iMemberId,$iTeamId],0,[]);
       
       
        $this->assertFalse($bFound,'Unable to withdrawal a member from team'); 
   
    }
    
    
    
}
/* end of file */
