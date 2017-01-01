<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Directive;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractDirective;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Fetch Rule Type Details
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class JoinRuleTypeDirective extends AbstractDirective
{
    
    const RULETYPE_TABLE_ALIAS = 'rt';
    
    
    public function build()
    {
            
        $oQuery                  = $this->getQueryBuilder();
        $sRuleTypeTable          = $oQuery->getTableName('bm_rule_type'); 
        $sDefaultAlias           = $this->getAlias();    
        $sRuleTypeAlias          = self::RULETYPE_TABLE_ALIAS;
        
        // Register join alias
        
        $oQuery->useAlias($sRuleTypeAlias,'bm_rule_type');
        
        // Join table
        
        $oQuery->innerJoin(
            $sDefaultAlias
            ,$sRuleTypeTable
            ,$sRuleTypeAlias
            ,$oQuery->expr()->eq($this->getField($sDefaultAlias,'rule_type_id'),$this->getField($sRuleTypeAlias,'rule_type_id'))
        )
        ->addSelect($this->getField($sRuleTypeAlias,'rule_code','ruleCode')); 
        
    }
    
}
/* end of class */