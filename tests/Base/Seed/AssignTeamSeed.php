<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;


class AssignTeamSeed extends BaseSeed
{
    
   
   protected $aAssignments;
    
    
    
    protected function assignTeam($iTeamId, $iMemberId)
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        
        $sTeamMemberTableName   = $aTableNames['bm_schedule_team_members'];
        
        $sSql  = " INSERT INTO $sTeamMemberTableName (`team_id`, `membership_id`, `registered_date`) VALUES (:iTeamId, :iMemberId, NOW()) ";
        
	    
        $oIntegerType = Type::getType(Type::INTEGER);
    
        $aParams = [
                ':iTeamId'     => $iTeamId,
                ':iMemberId'   => $iMemberId,  
        ];
    
        $iAffected = $oDatabase->executeUpdate($sSql, $aParams, [$oIntegerType, $oIntegerType]);
        
        if(true === empty($iAffected)) {
            throw RuntimeException('Unable to assign meember to team');
        }
       
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        foreach($this->aAssignments as $sKey => $aAssignment) {
            $this->assignTeam($aAssignment['TEAM_ID'], $aAssignment['MEMBERSHIP_ID']);
            
        }
        
        return true;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aAssignments)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aAssignments = $aAssignments;
   
    }
    
    
}
/* End of Class */
