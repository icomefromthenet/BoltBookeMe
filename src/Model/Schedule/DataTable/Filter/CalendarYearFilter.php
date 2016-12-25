<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\DataTable\Filter;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Filter to schedules within the following calendar year.
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
        
        if(true === isset($aParams['iCalYear'])) {
        
            $oQuery->andWhere($this->getField('calendar_year').'= :iCalYear')
                   ->setParameter('iCalYear',$aParams['iCalYear'],Type::INTEGER);
        }
        
    }
    
}
/* end of class */