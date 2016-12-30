<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to limit rules that belong to a given calendar Year
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com.au>
 * @since 1.0
 */
class CalendarYearFilter extends AbstractFilter
{
    
    public function build()
    {
        $aParams = $this->params;
        $oQuery  = $this->getQueryBuilder();
        
        if(isset($aParams['iCalYear']) && !empty($aParams['iCalYear'])) {
            
            $oQuery->andWhere($oQuery->expr()->eq('year('.$this->getField('start_from').')',':iCalYear'))
                   ->setParameter('iCalYear',$aParams['iCalYear'],Type::INTEGER);
                  
        }
        
    }
    
}
/* end of class */