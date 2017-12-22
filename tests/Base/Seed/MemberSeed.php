<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;


class MemberSeed extends BaseSeed
{
    
    
    protected $aMembersList;
    
    
    protected function createMember($sMemberName, $iBoltMemberId)
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $sMemberTableName   = $aTableNames['bm_schedule_membership'];
        $iMemberId          = null;
        
        
        $sSql = " INSERT INTO $sMemberTableName (membership_id, registered_date, member_name, bolt_user_id)
                 VALUES (null, NOW(), :sMemberName, :iBoltUserId) ";

    
        $oStringType = TYPE::getType(TYPE::STRING);
        $iIntType    = TYPE::getType(TYPE::INTEGER);
    
        $oDatabase->executeUpdate($sSql, [':sMemberName' => $sMemberName, ':iBoltUserId' => $iBoltMemberId ], [$oStringType,$iIntType]);
        
        $iMemberId = $oDatabase->lastInsertId();
        
      
        return $iMemberId;
   
    }

    
    protected function doExecuteSeed()
    {
        $aMembers = [];
        foreach($this->aMembersList as $sKey => $aMember) {
            $aMembers[$sKey] =  $this->createMember($aMember['MEMBER_NAME'], $aMember['BOLT_USER_ID']);
        }
        
        return $aMembers;       
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, $aMembersList)
    {
       
        parent::__construct($oDatabase, $aTableNames);
      
        $this->aMembersList = $aMembersList;
    }
    
    
    
    
}
/* End of Class */
