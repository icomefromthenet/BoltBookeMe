<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Fetch Member Details
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class JoinMemberDetailDirective extends AbstractDirective
{
    
    const MEMBER_TABLE_ALIAS = 'mt';
    
    
    public function build()
    {
            
        $oQuery                  = $this->getQueryBuilder();
        $sMemberTable            = $oQuery->getTableName('bm_schedule_membership'); 
        $sDefaultAlias           = $this->getAlias();    
        $sMemberTableAlias       = self::MEMBER_TABLE_ALIAS;
        
        // Register join alias
        
        $oQuery->useAlias($sTableAlias,'bm_schedule_membership');
        
        // Join table
        
        $oQuery->innerJoin(
            $sDefaultAlias
            ,$sMemberTable
            ,$sMemberTableAlias
            ,$oQuery->expr()->eq($this->getField('member_id',$sDefaultAlias),$this->getField('member_id',$sMemberTableAlias))
        )
        ->addSelect($this->getField('member_name',$sMemberTableAlias))
        ->addSelect($this->getField('registered_date',$sMemberTableAlias,'member_registered_date')); 
        
    }
    
}
/* End of Class */