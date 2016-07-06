<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\AssignTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MembershipException;


/**
 * Used to assign a member to a team
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class AssignTeamMemberHandler 
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    the database adpater
     */ 
    protected $oDatabaseAdapter;
    
    
    
    public function __construct(array $aTableNames, Connection $oDatabaseAdapter)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        
    }
    
    
    public function handle(AssignTeamMemberCommand $oCommand)
    {
        
        $oDatabase              = $this->oDatabaseAdapter;
        $sTeamMemberTableName   = $this->aTableNames['bm_schedule_team_members'];
        
        $iTeamId           = $oCommand->getTeamId();
        $iMemberId         = $oCommand->getMemberId();
        
        
        $sSql  = " INSERT INTO $sTeamMemberTableName (`team_id`, `membership_id`, `registered_date`) VALUES (:iTeamId, :iMemberId, NOW()) ";
        
	    try {
	    
	        $oIntegerType = Type::getType(Type::INTEGER);
	    
	        $aParams = [
	                ':iTeamId'     => $iTeamId,
	                ':iMemberId'   => $iMemberId,  
	        ];
	    
	        $iAffected = $oDatabase->executeUpdate($sSql, $aParams, [$oIntegerType, $oIntegerType]);
            
            if(true === empty($iAffected)) {
                throw MembershipException::hasFailedAssignTeamMember($oCommand, $e);
            }
                 
	    }
	    catch(DBALException $e) {
	        throw MembershipException::hasFailedAssignTeamMember($oCommand, $e);
	    }
    	
        
        
        return true;
    }
     
    
}
/* End of File */