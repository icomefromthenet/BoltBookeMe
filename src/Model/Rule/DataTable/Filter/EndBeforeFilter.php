<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter end before
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class EndBeforeFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        
        if(isset($aParams['oEndBefore']) && is_a($aParams['oEndBefore'],'\DateTime') ) {
            
            $oQuery->andWhere($oQuery->expr()->lte($this->getField('end_at'),':EndAt'))
                   ->setParameter('EndAt',$aParams['oEndBefore'],Type::DATE);
        }
        
    }
    
}
/* end of class */