<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\MembershipException;


/**
 * Used to register a new member
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RegisterMemberHandler 
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
    
    
    public function handle(RegisterMemberCommand $oCommand)
    {
        
        $oDatabase          = $this->oDatabaseAdapter;
        $sMemberTableName   = $this->aTableNames['bm_schedule_membership'];
        $iMemberId          = null;
        $sMemberName        = $oCommand->getMemberName();
        $iBoltMemberId      = $oCommand->getBoltMemberId();
        
        $sSql = " INSERT INTO $sMemberTableName (membership_id, registered_date, member_name, bolt_user_id) VALUES (null, NOW(), :sMemberName, :iBoltUserId) ";

	    
	    try {
	    
	        $oStringType = TYPE::getType(TYPE::STRING);
	        $iIntType    = TYPE::getType(TYPE::INTEGER);
	    
	        $oDatabase->executeUpdate($sSql, [':sMemberName' => $sMemberName, ':iBoltUserId' => $iBoltMemberId ], [$oStringType,$iIntType]);
            
            $iMemberId = $oDatabase->lastInsertId();
            
            $oCommand->setMemberId($iMemberId);
                 
	    }
	    catch(DBALException $e) {
	        throw MembershipException::hasFailedRegisterMember($oCommand, $e);
	    }
    	
        
        
        return true;
    }
     
    
}
/* End of File */