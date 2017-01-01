<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Default Fields in Member Table that form base of our search query
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class BaseRuleDirective extends AbstractDirective
{
    
    public function build()
    {
            
        $oQuery         = $this->getQueryBuilder();
        $sRuleTableName = $oQuery->getTableName('bm_schedule_membership'); 
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