<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to limit rules that start on and after date x
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class ApplyFromFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        $sAlias  = $this->getAlias();
        
        if(isset($aParams['oApplyFrom']) && is_a($aParams['oApplyFrom'],'\DateTime') ) {
            
            $oQuery->andWhere($oQuery->expr()->gte($this->getField($sAlias,'start_from'),':StartFrom'))
                   ->setParameter('StartFrom',$aParams['oApplyFrom'],Type::DATE);
        }
        
    }
    
}
/* end of class */