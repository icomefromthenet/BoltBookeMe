<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to limit rules that belong to a given rule type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class RuleTypeFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        $sAlias  = $this->getAlias();
        
        if(isset($aParams['iRuleTypeId']) && !empty($aParams['iRuleTypeId'])) {
            
            
            $oQuery->andWhere($oQuery->expr()->eq($this->getField($sAlias,'rule_type_id'),':iRuleTypeId'))
                   ->setParameter('iRuleTypeId',$aParams['iRuleTypeId']->getRuleTypeId(),Type::INTEGER);
        }
        
    }
    
}
/* end of class */