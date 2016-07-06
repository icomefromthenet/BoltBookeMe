<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterTeamCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MembershipException;


/**
 * Used to register a new member
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RegisterTeamHandler 
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
    
    
    public function handle(RegisterTeamCommand $oCommand)
    {
        
        $oDatabase         = $this->oDatabaseAdapter;
        $sTeamTableName    = $this->aTableNames['bm_schedule_team'];
        $iTeamId           = null;
        $sTeamName         = $oCommand->getTeamName();
        
        $sSql = " INSERT INTO $sTeamTableName (team_id, team_name ,registered_date) VALUES (null, ? ,NOW()) ";

	    
	    try {
	    
	        $oDatabase->executeUpdate($sSql, [$sTeamName], [Type::getType(Type::STRING)]);
            
            $iTeamId = $oDatabase->lastInsertId();
            
            $oCommand->setTeamId($iTeamId);
                 
	    }
	    catch(DBALException $e) {
	        throw MembershipException::hasFailedRegisterTeam($oCommand, $e);
	    }
    	
        
        
        return true;
    }
     
    
}
/* End of File */