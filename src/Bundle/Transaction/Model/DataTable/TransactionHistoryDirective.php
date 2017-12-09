<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Base Query for Transaction History
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class TransactionHistoryDirective extends AbstractDirective
{
    
    public function build()
    {
            
        $oQuery         = $this->getQueryBuilder();
        
        
        $sLedgerHeaderTable        = $oQuery->getTableName('le'); 
        $sLedgerAccountMovement    = 
        
        $sAlias         = $this->getAlias();
            
        $oQuery->select(
            $this->getField($sAlias,'membership_id','membershipId'),
            $this->getField($sAlias,'registered_date','registeredDate'),
            $this->getField($sAlias,'member_name','memberName')
        )
        ->from($sRuleTableName,$sAlias);

    }
    
}
/* end of class */